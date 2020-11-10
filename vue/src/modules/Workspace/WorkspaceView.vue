<template>
  <div v-if="loading">
    <content-spinner show/>
  </div>
  <div v-else class="workspace-view page">
    <div class="page-header">
      <b-breadcrumb :items="breadcrumbs" class="d-none d-sm-flex"></b-breadcrumb>
      <WorkspaceUsers :model="[]"/>
    </div>
    <sided-nav-layout :items="items"/>
  </div>
</template>

<script>
import {createNamespacedHelpers} from "vuex";
import WorkspaceUsers from "./WorkspaceUsers";
import SidedNavLayout from "@/core/components/sided-nav-layout/SidedNavLayout";
import ContentSpinner from "@/core/components/ContentSpinner";

const {mapState, mapActions} = createNamespacedHelpers('workspace')

export default {
  name: "WorkspaceView",
  components: {ContentSpinner, SidedNavLayout, WorkspaceUsers},
  data() {
    return {
      breadcrumbs: [],
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
      workspace: state => state.view.workspace,
      loading: state => state.view.loading,
    }),
  },
  methods: {
    ...mapActions(['getCurrentWorkspace', 'destroyCurrentWorkspace']),
    initBreadcrumbs() {
      this.breadcrumbs = [
        {text: this.$i18n.t('My Workspaces'), to: {name: 'workspace'}},
        {text: this.workspace.abbreviation || this.workspace.name, active: true}
      ];
    },
  },
  async beforeMount() {
    await this.getCurrentWorkspace(this.$route.params.id);
    this.initBreadcrumbs();
  },
  destroyed() {
    this.destroyCurrentWorkspace({});
  }
}
</script>

<style lang="scss" scoped>
.page-content {
  display: grid;
  grid-gap: 1em;
  grid-template-columns: repeat(6, 1fr);

  .content {
    grid-column: 2/7;
  }
}
</style>