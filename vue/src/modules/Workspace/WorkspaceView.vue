<template>
  <div class="workspace-view page">
    <div class="page-header">
      <back-button/>
      <b-breadcrumb :items="breadCrumb" class="d-none d-sm-flex"></b-breadcrumb>
      <WorkspaceUsers :model="employees"/>
    </div>

    <sided-nav-layout :items="items"/>


    <!--    <div class="page-content">-->
    <!--      <div class="content-wrapper p-3">-->
    <!--        <div class="row">-->
    <!--          <div class="col-sm-12 col-lg-6 col-xl-7 mb-4 order-lg-3 col-folders">-->
    <!--            <h4 class="border-bottom pb-1 mb-3 pb-2">{{ $t('Folders') }}</h4>-->
    <!--            <div class="folder-list">-->
    <!--              <content-spinner :show="loading" :text="$t('Loading...')" class="h-100"/>-->
    <!--              <no-data :model="articles" :loading="loading" :text="$t('There are no folders')"/>-->
    <!--              <div v-if="articles" class="folder-wrapper row">-->
    <!--                <ArticleItem class="mb-2 col-md-12 col-xl-6" v-for="(article, index) in articles"-->
    <!--                             :article="article" :index="index" :key="`article-item-${article.id}`">-->
    <!--                </ArticleItem>-->
    <!--              </div>-->
    <!--            </div>-->
    <!--          </div>-->

    <!--        </div>-->
    <!--      </div>-->
    <!--    </div>-->
  </div>
</template>

<script>
import BackButton from "./components/BackButton";
import {createNamespacedHelpers} from "vuex";
import ArticleItem from "./article/ArticleItem";
import ContentSpinner from "../../core/components/ContentSpinner";
import NoData from "./components/NoData";
import WorkspaceUsers from "./WorkspaceUsers";
import SidedNavLayout from "@/core/components/sided-nav-layout/SidedNavLayout";

const {mapState, mapActions} = createNamespacedHelpers('workspace')
const {mapState: mapArticleStates, mapActions: mapArticleActions} = createNamespacedHelpers('article')

export default {
  name: "WorkspaceView",
  components: {SidedNavLayout, WorkspaceUsers, NoData, ContentSpinner, ArticleItem, BackButton},
  data() {
    return {
      visible: false,
      items: [
        {title: this.$i18n.t('Timeline'), to: {name: 'workspace.timeline'}, icon: 'fa fa-bars'},
        {title: this.$i18n.t('Files'), to: {name: 'workspace.files'}, icon: 'fa fa-folder'},
        {title: this.$i18n.t('Articles'), to: {name: 'workspace.articles'}, icon: 'fa fa-book'},
        {title: this.$i18n.t('About'), to: {name: 'workspace.about'}, icon: 'fa fa-info-circle'},
      ]
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
    },
  },
  methods: {
    ...mapActions(['getWorkspaceBreadCrumb', 'getCurrentWorkspace', 'destroyCurrentWorkspace', 'getEmployees']),
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
    const workspaceId = this.$route.params.id;

    this.getBreadCrumb();
    this.getArticlesByWorkspace(workspaceId);
    this.getCurrentWorkspace(workspaceId);
    this.getEmployees(workspaceId);
  },
  destroyed() {
    this.destroyCurrentWorkspace({});
  }
}
</script>

<style lang="scss" scoped>
.workspace-image {
  width: 160px;
  min-width: 160px;
}

.page-content {
  display: grid;
  grid-gap: 1em;
  grid-template-columns: repeat(6, 1fr);

  .content {
    grid-column: 2/7;
  }
}

</style>