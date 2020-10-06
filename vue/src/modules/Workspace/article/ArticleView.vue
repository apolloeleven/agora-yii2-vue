<template>
  <div id="article-view" class="page">
    <div class="page-header">
      <back-button/>
      <b-breadcrumb :items="breadCrumb" class="d-none d-sm-flex"></b-breadcrumb>
      <b-dropdown variant="link" no-caret right v-if="isFolder">
        <template v-slot:button-content>
          <b-button size="sm" pill variant="link">
            <i class="fas fa-plus-circle fa-3x"></i>
          </b-button>
        </template>
        <b-dropdown-item v-if="lastFolder && multipleFolders"
                         @click="showModal()">
          <i class="fas fa-plus"></i>
          {{ $t('Create new folder') }}
        </b-dropdown-item>
        <b-dropdown-item @click="showModal(true)">
          <i class="fas fa-plus"></i>
          {{ $t('Create new article') }}
        </b-dropdown-item>
      </b-dropdown>
      <AddToFavourites :name="favouriteName"/>
    </div>
    <div class="page-content">
      <content-spinner :show="loading" :text="$t('Loading...')" class="h-100"/>
      <b-card no-body class="mb-1" v-if="isFolder">
        <b-card-header header-tag="header" class="p-1" aria-controls="collapse"
                       :aria-expanded="visible ? 'true' : 'false'" @click="visible = !visible">
          <i v-if="!visible" class="fas fa-angle-double-down fa-2x"></i>
          <i v-else class="fas fa-angle-double-up fa-2x"></i>
        </b-card-header>
        <b-collapse id="collapse" v-model="visible">
          <b-card-body>
            <div class="row">
              <div class="col-md-4 col-sm-12">
                <b-media class="article-header align-items-center">
                  <i class="fas fa-folder-open fa-4x"></i>
                </b-media>
              </div>
              <div class="col-md-8 col-sm-12">
                <div>
                  <h3 class="mt-0">{{ currentArticle.title }}</h3>
                </div>
                <div v-html="currentArticle.body"></div>
              </div>
            </div>
          </b-card-body>
        </b-collapse>
      </b-card>
      <div class="p-3">
        <div class="row" v-if="lastFolder && isFolder && multipleFolders">
          <div class="col-md-12 col-lg-4">
            <h4 class="border-bottom pb-1 mb-3">{{ $t('Articles') }}</h4>
            <div class="article-list">
              <content-spinner :show="loading" :text="$t('Loading...')" class="h-100"/>
              <no-data :model="filteredArticles" :loading="loading" :text="$t('There are no articles')"></no-data>
              <div class="article-list">
                <ArticleChildItem
                  v-for="(article, index) in filteredArticles" :index="index" :model="article" :key="article.id"/>
              </div>
            </div>
          </div>
          <div class="col-md-12 col-lg-4">
            <h4 class="border-bottom pb-1 mb-3">{{ $t('Folders') }}</h4>
            <div class="article-list">
              <content-spinner :show="loading" :text="$t('Loading...')" class="h-100"/>
              <no-data :model="filteredFolders" :loading="loading" :text="$t('There are no folders')"></no-data>
              <div class="article-list">
                <ArticleChildItem
                  v-for="(folder, index) in filteredFolders" :index="index" :model="folder" :key="folder.id"/>
              </div>
            </div>
          </div>
          <div class="col-md-12 col-lg-4">
            <Attachments/>
          </div>
        </div>
        <div class="row" v-else-if="!isFolder">
          <div class="col-sm-12 col-lg-6">
            <b-card class="article-item mb-3" no-body>
              <template v-slot:header>
                <h5 class="mb-0">{{ currentArticle.title }}</h5>
              </template>
              <b-card-body>
                <div class="article-content">
                  <div v-if="currentArticle.body" class="article-body" v-html="currentArticle.body"></div>
                  <no-data v-else :model="currentArticle" :loading="loading"
                           :text="$t('There is no description')">
                  </no-data>
                </div>
              </b-card-body>
            </b-card>
            <div class="article-list">
              <ArticleChildItem
                v-for="(article, index) in filteredArticles" :index="index" :model="article" :key="article.id"/>
            </div>
          </div>
          <div class="col-sm-12 col-lg-6">
            <Attachments/>
          </div>
        </div>
        <div class="row" v-else-if="!lastFolder || !multipleFolders">
          <div class="col-sm-12 col-lg-6">
            <h4 class="border-bottom pb-1 mb-3">{{ $t('Articles') }}</h4>
            <div class="article-list">
              <no-data :model="filteredArticles" :loading="loading" :text="$t('There are no articles')"></no-data>
              <div v-if="filteredArticles" class="article-list">
                <ArticleChildItem
                  v-for="(article, index) in filteredArticles" :index="index" :model="article" :key="article.id"/>
              </div>
            </div>
          </div>
          <div class="col-sm-12 col-lg-6">
            <Attachments/>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import BackButton from "../components/BackButton";
import {createNamespacedHelpers} from "vuex";
import ArticleChildItem from "./ArticleChildItem";
import ContentSpinner from "../../../core/components/ContentSpinner";
import NoData from "../components/NoData";
import AddToFavourites from "../components/AddToFavourites/AddToFavourites";
import Attachments from "./attachment/Attachments";

const {mapState, mapActions} = createNamespacedHelpers('article')

export default {
  name: "ArticleView",
  components: {Attachments, AddToFavourites, NoData, ContentSpinner, ArticleChildItem, BackButton},
  data() {
    return {
      visible: false,
    }
  },
  computed: {
    ...mapState(['breadCrumb', 'currentArticle', 'articles', 'loading']),
    filteredArticles() {
      return this.articles.filter(a => a.is_folder === 0)
    },
    filteredFolders() {
      return this.articles.filter(a => a.is_folder === 1)
    },
    lastFolder() {
      return this.currentArticle.depth < 3;
    },
    isFolder() {
      return this.currentArticle.is_folder;
    },
    favouriteName() {
      const wk = this.currentArticle.workspace || {};
      return (wk.abbreviation || wk.name) + ' / ' + this.currentArticle.title;
    },
    multipleFolders() {
      return !!this.currentArticle.workspace.folder_in_folder
    },
  },
  watch: {
    '$route.params.id': function (id) {
      this.getCurrentArticle(id);
      this.getBreadCrumb(id);
      this.getArticlesByParent(id)
    }
  },
  methods: {
    ...mapActions(['getArticleBreadCrumb', 'getCurrentArticle', 'showArticleModal', 'getArticlesByParent', 'destroyCurrentArticle']),
    async getBreadCrumb() {
      const res = await this.getArticleBreadCrumb(this.$route.params.id)
      if (!res.success) {
        this.$toast(this.$t(res.body.message), 'danger')
        this.$router.push({name: 'workspace'});
      }
    },
    showModal(isArticle = false) {
      this.showArticleModal({isArticle: isArticle, article: null})
    }
  },
  mounted() {
    this.getBreadCrumb();
    this.getCurrentArticle(this.$route.params.id);
    this.getArticlesByParent(this.$route.params.id);
  },
  destroyed() {
    this.destroyCurrentArticle({});
  }
}
</script>

<style lang="scss" scoped>
.article-item {
  .media-body {
    display: flex;
    align-items: start;
  }
}

.article-description {
  word-break: break-word;
}
</style>