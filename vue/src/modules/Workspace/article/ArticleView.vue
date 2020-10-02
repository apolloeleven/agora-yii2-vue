<template>
  <div id="article-view" class="page">
    <div class="page-header">
      <back-button/>
      <b-breadcrumb :items="breadCrumb" class="d-none d-sm-flex"></b-breadcrumb>
      <b-dropdown variant="link" no-caret right>
        <template v-slot:button-content>
          <b-button size="sm" pill variant="link">
            <i class="fas fa-plus-circle fa-2x"></i>
          </b-button>
        </template>
        <b-dropdown-item v-if="currentArticle.depth < 3 && currentArticle.workspace.folder_in_folder" @click="showArticleModal(false)">
          <i class="fas fa-plus-circle"></i>
          {{ $t('Create new folder') }}
        </b-dropdown-item>
        <b-dropdown-item @click="showArticleModal(true)">
          <i class="fas fa-plus-circle"></i>
          {{ $t('Create new article') }}
        </b-dropdown-item>
      </b-dropdown>
    </div>
    <div class="page-content">
      <b-card class="article-folder p-2" v-if="!currentArticle.parent_id">
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
      </b-card>
      <div class="p-3">
        <div class="row">
          <div class="col-sm-12 col-lg-6">
            <b-card class="article-item mb-3" v-if="currentArticle.parent_id" no-body>
              <template v-slot:header>
                <h5 class="mb-0">{{ currentArticle.title }}</h5>
              </template>

              <b-card-body>
                <div class="article-content">
                  <i class="fas fa-folder-open fa-4x"></i>
                </div>
              </b-card-body>
            </b-card>
            <div class="article-list">
              <ArticleChildItem v-for="(art, index) in articles" :index="index" :article="art"/>
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

const {mapState, mapActions} = createNamespacedHelpers('article')

export default {
  name: "ArticleView",
  components: {ArticleChildItem, BackButton},
  computed: {
    ...mapState(['breadCrumb', 'currentArticle', 'articles']),
  },
  watch: {
    '$route.params.id': function (id) {
      this.getCurrentArticle(id);
      this.getBreadCrumb(id);
    }
  },
  methods: {
    ...mapActions(['getArticleBreadCrumb', 'getCurrentArticle', 'showArticleModal']),
    async getBreadCrumb() {
      const res = await this.getArticleBreadCrumb(this.$route.params.id)
      if (!res.success) {
        this.$toast(this.$t(res.body), 'danger')
      }
    },
  },
  mounted() {
    this.getBreadCrumb();
    this.getCurrentArticle(this.$route.params.id);
  }
}
</script>

<style scoped>

</style>