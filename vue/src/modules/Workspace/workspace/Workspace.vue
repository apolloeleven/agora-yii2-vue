<template>
  <div id="workspace" class="page">
    <content-spinner :show="loading" :text="$t('Loading...')" :fullscreen="true" class="h-100"/>
    <div class="page-header">
      <b-breadcrumb :items="breadCrumb" class="d-none d-sm-flex"></b-breadcrumb>
      <b-button @click="showModal" variant="info">
        <i class="fas fa-plus-circle"></i>
        {{ $t('Add New Workspace') }}
      </b-button>
    </div>

    <div class="page-content p-3">
      <div class="workspace-list">
        <div class="row">
          <div v-for="(workspace, index) in workspaces" :key="`workspace-wrapper-${workspace.id}`" class="col-lg-2 col-sm-6 mb-3">
            <WorkspaceItem :workspace="workspace" :index="index" />
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import {createNamespacedHelpers} from "vuex";
import ContentSpinner from "../../../core/components/ContentSpinner";
import WorkspaceItem from "./WorkspaceItem";

const {mapState, mapActions} = createNamespacedHelpers('workspace');

export default {
  name: "Workspace",
  components: {WorkspaceItem, ContentSpinner},
  data() {
    return {
      breadCrumb: [
        {
          text: this.$t('My Workspaces')
        }
      ]
    }
  },
  computed: {
    ...mapState(['loading', 'workspaces'])
  },
  methods: {
    ...mapActions(['showWorkspaceModal', 'getWorkspaces']),
    showModal() {
      this.showWorkspaceModal(null)
    },
  },
  created() {
    this.getWorkspaces();
  },
}
</script>

<style scoped>

</style>