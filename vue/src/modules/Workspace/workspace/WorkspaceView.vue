<template>
  <div class="workspace-view page">
    <div class="page-header">
      <back-button/>
      <b-breadcrumb :items="breadCrumb" class="d-none d-sm-flex"></b-breadcrumb>
      <WorkspaceUsers :model="employees"/>
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
              <no-data :model="articles" :loading="loading" :text="$t('There are no folders')"></no-data>
              <div v-if="articles" class="folder-wrapper row">
                <ArticleItem
                  class="mb-2 col-md-12 col-xl-6" v-for="(article, index) in articles"
                  :article="article" :index="index" :key="article.id"/>
              </div>
            </div>
          </div>
          <div class="col-sm-12 col-lg-6 col-xl-5 order-lg-2 mb-4 col-timeline">
            <h4 class="border-bottom pb-1 mb-3">{{ $t('Timeline') }}
              <b-button class="float-right" @click="showTimelineModal" size="sm" variant="outline-primary">
                <i class="fas fa-plus-circle"/>
                {{ $t('Write on timeline') }}
              </b-button>
            </h4>
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
import NoData from "../components/NoData";
import WorkspaceUsers from "./WorkspaceUsers";

const {mapState, mapActions} = createNamespacedHelpers('workspace')
const {mapState: mapArticleStates, mapActions: mapArticleActions} = createNamespacedHelpers('article')
const {mapActions: mapTimelineActions} = createNamespacedHelpers('timeline');

export default {
  name: "WorkspaceView",
  components: {WorkspaceUsers, NoData, ContentSpinner, ArticleItem, BackButton},
  data() {
    return {
      visible: false,
    }
  },
  computed: {
    ...mapState(['breadCrumb', 'currentWorkspace', 'employees']),
    ...mapArticleStates(['articles', 'loading']),
  },
  watch: {
    '$route.params.id': function (id) {
      this.getArticlesByWorkspace(id);
      this.getCurrentWorkspace(id);
      this.getEmployees(id);
      this.getTimelinePosts(id);
    },
  },
  methods: {
    ...mapActions(['getWorkspaceBreadCrumb', 'getCurrentWorkspace', 'destroyCurrentWorkspace', 'getEmployees']),
    ...mapArticleActions(['showArticleModal', 'getArticlesByWorkspace']),
    ...mapTimelineActions(['showTimelineModal', 'getTimelinePosts']),
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
    const workspaceId = this.$route.params.id;

    this.getBreadCrumb();
    this.getArticlesByWorkspace(workspaceId);
    this.getCurrentWorkspace(workspaceId);
    this.getEmployees(workspaceId);
    this.getTimelinePosts(workspaceId);
  },
  destroyed() {
    this.destroyCurrentWorkspace({});
  }
}
</script>

<style scoped>

</style>