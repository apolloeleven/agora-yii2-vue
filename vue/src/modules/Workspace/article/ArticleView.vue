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
        <b-dropdown-item v-if="lastFolder && currentArticle.workspace.folder_in_folder"
                         @click="showModal()">
          <i class="fas fa-plus"></i>
          {{ $t('Create new folder') }}
        </b-dropdown-item>
        <b-dropdown-item @click="showModal(true)">
          <i class="fas fa-plus"></i>
          {{ $t('Create new article') }}
        </b-dropdown-item>
      </b-dropdown>
    </div>
    <div class="page-content">
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
        <div class="row" v-if="lastFolder && isFolder">
          <div class="col-sm-6 col-lg-4">
            <h4 class="border-bottom pb-1 mb-3">{{ $t('Articles') }}</h4>
            <div class="article-list">
              <content-spinner :show="loading" :text="$t('Loading...')" class="h-100"/>
              <div v-if="!filteredArticles.length && !loading" class="no-data">
                {{ $t('There are no articles') }}
              </div>
              <div class="article-list">
                <ArticleChildItem v-for="(article, index) in filteredArticles" :index="index" :article="article"/>
              </div>
            </div>
          </div>
          <div class="col-sm-6 col-lg-4">
            <h4 class="border-bottom pb-1 mb-3">{{ $t('Folders') }}</h4>
            <div class="article-list">
              <content-spinner :show="loading" :text="$t('Loading...')" class="h-100"/>
              <div v-if="!filteredFolders.length && !loading" class="no-data">
                {{ $t('There are no folders') }}
              </div>
              <div class="article-list">
                <ArticleChildItem v-for="(folder, index) in filteredFolders" :index="index" :article="folder"/>
              </div>
            </div>
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
                  <div v-else class="no-data">{{ $t('There is no description') }}</div>
                </div>
              </b-card-body>
            </b-card>
            <div class="article-list">
              <ArticleChildItem v-for="(art, index) in filteredArticles" :index="index" :article="art"/>
            </div>
          </div>
        </div>
        <div class="row" v-else-if="!lastFolder">
          <div class="col-sm-12 col-lg-6">
            <h4 class="border-bottom pb-1 mb-3">{{ $t('Articles') }}</h4>
            <div class="article-list">
              <content-spinner :show="loading" :text="$t('Loading...')" class="h-100"/>
              <div v-if="!filteredArticles.length && !loading" class="no-data">
                {{ $t('There are no articles') }}
              </div>
              <div class="article-list">
                <ArticleChildItem v-for="(article, index) in filteredArticles" :index="index" :article="article"/>
              </div>
            </div>
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

const {mapState, mapActions} = createNamespacedHelpers('article')

export default {
  name: "ArticleView",
  components: {ContentSpinner, ArticleChildItem, BackButton},
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
    }
  },
  watch: {
    '$route.params.id': function (id) {
      this.getCurrentArticle(id);
      this.getBreadCrumb(id);
      this.getArticlesByParent(id)
    }
  },
  methods: {
    ...mapActions(['getArticleBreadCrumb', 'getCurrentArticle', 'showArticleModal', 'getArticlesByParent']),
    async getBreadCrumb() {
      const res = await this.getArticleBreadCrumb(this.$route.params.id)
      if (!res.success) {
        this.$toast(this.$t(res.body), 'danger')
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

.no-data {
  display: flex;
  align-items: center;
  justify-content: center;
  text-align: center;
  flex-direction: column;
  color: #b6b6b6;
  font-weight: bold;
  text-shadow: 1px 1px 0 #FFFFFF;
  font-size: 1.275rem;
  min-height: 200px;
}
</style>