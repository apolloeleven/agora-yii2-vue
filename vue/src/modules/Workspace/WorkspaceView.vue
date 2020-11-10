<template>
  <div v-if="loading">
    <content-spinner show/>
  </div>
  <div v-else class="workspace-view page">
    <div class="page-header">
      <back-button/>
      <b-breadcrumb :items="breadCrumb" class="d-none d-sm-flex"></b-breadcrumb>
      <WorkspaceUsers :model="[]"/>
    </div>
    <sided-nav-layout :items="items"/>
  </div>
</template>

<script>
import BackButton from "./components/BackButton";
import {createNamespacedHelpers} from "vuex";
import WorkspaceUsers from "./WorkspaceUsers";
import SidedNavLayout from "@/core/components/sided-nav-layout/SidedNavLayout";
import ContentSpinner from "@/core/components/ContentSpinner";

const {mapState, mapActions} = createNamespacedHelpers('workspace')

export default {
  name: "WorkspaceView",
  components: {ContentSpinner, SidedNavLayout, WorkspaceUsers, BackButton},
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
    ...mapState({
      breadCrumb: state => state.breadCrumb,
      workspace: state => state.view.workspace,
      loading: state => state.view.loading,
    }),
  },
  methods: {
    ...mapActions(['getWorkspaceBreadCrumb', 'getCurrentWorkspace', 'destroyCurrentWorkspace']),
    async getBreadCrumbs() {
      const res = await this.getWorkspaceBreadCrumb(this.$route.params.id)
      if (!res.success) {
        this.$toast(this.$t(res.body), 'danger')
        this.$router.push({name: 'workspace'});
      }
    },
  },
  beforeMount() {
    this.getBreadCrumbs();
    this.getCurrentWorkspace(this.$route.params.id);
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