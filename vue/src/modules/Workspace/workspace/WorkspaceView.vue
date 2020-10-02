<template>
  <div class="workspace-view page">
    <div class="page-header">
      <back-button/>
      <b-breadcrumb :items="breadCrumb" class="d-none d-sm-flex"></b-breadcrumb>
      <b-button @click="showModal" variant="info">
        <i class="fas fa-plus-circle"></i>
        {{ $t('Create new folder') }}
      </b-button>
    </div>
    <div class="page-content">
      <b-card no-body class="mb-1">
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
                  <h3 class="mt-0">{{ currentWorkspace.name }}</h3>
                </div>
                <div v-html="currentWorkspace.description"></div>
              </div>
            </div>
          </b-card-body>
        </b-collapse>
      </b-card>
      <div class="content-wrapper p-3">
        <div class="row">
          <div class="col-sm-12 col-lg-6 col-xl-7 mb-4 order-lg-3 col-folders">
            <h4 class="border-bottom pb-1 mb-3">{{ $t('Folders') }}</h4>
            <div class="folder-list">
              <content-spinner :show="loading" :text="$t('Loading...')" class="h-100"/>
              <div v-if="!articles.length && !loading" class="no-data">
                {{ $t('There are no folders') }}
              </div>
              <div v-if="articles" class="folder-wrapper row">
                <ArticleItem
                  class="mb-2 col-md-12 col-xl-6" v-for="(article, index) in articles"
                  :article="article" :index="index" :key="article.id"/>
              </div>
            </div>
          </div>
          <div class="col-sm-12 col-lg-6 col-xl-5 order-lg-2 mb-4 col-timeline">
            <h4 class="border-bottom pb-1 mb-3">{{ $t('Timeline') }}</h4>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import BackButton from "../components/BackButton";
import {createNamespacedHelpers} from "vuex";
import ArticleItem from "../article/ArticleItem";
import ContentSpinner from "../../../core/components/ContentSpinner";

const {mapState, mapActions} = createNamespacedHelpers('workspace')
const {mapState: mapArticleStates, mapActions: mapArticleActions} = createNamespacedHelpers('article')

export default {
  name: "WorkspaceView",
  components: {ContentSpinner, ArticleItem, BackButton},
  data(){
    return {
      visible: false,
    }
  },
  computed: {
    ...mapState(['breadCrumb', 'currentWorkspace']),
    ...mapArticleStates(['articles', 'loading']),
  },
  watch: {
    '$route.params.id': function (id) {
      this.getArticlesByWorkspace(id);
      this.getCurrentWorkspace(id);
    },
  },
  methods: {
    ...mapActions(['getWorkspaceBreadCrumb', 'getCurrentWorkspace']),
    ...mapArticleActions(['showArticleModal', 'getArticlesByWorkspace']),
    async getBreadCrumb() {
      const res = await this.getWorkspaceBreadCrumb(this.$route.params.id)
      if (!res.success) {
        this.$toast(this.$t(res.body), 'danger')
        this.$router.push({name: 'workspace'});
      }
    },
    showModal() {
      this.showArticleModal({isArticle: false, article: null})
    },
  },
  mounted() {
    this.getBreadCrumb();
    this.getArticlesByWorkspace(this.$route.params.id);
    this.getCurrentWorkspace(this.$route.params.id);
  },
}
</script>

<style scoped>
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