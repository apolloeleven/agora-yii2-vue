<template>
  <b-card no-body class="workspace-about shrinked-width">
    <b-card-header class="d-flex align-items-center">
      <h5 class="mb-0">{{ $t('Workspace Details') }}</h5>
      <b-button @click="onEditClick" size="sm" variant="outline-primary" class="ml-auto">
        <i class="fas fa-edit"/>
        {{ $t('Edit') }}
      </b-button>
      <b-button @click="onDeleteClick" size="sm" variant="outline-danger" class="ml-2">
        <i class="fas fa-delete"/>
        {{ $t('Delete') }}
      </b-button>
    </b-card-header>
    <b-card-body>
      <h4>{{ workspace.name }}</h4>
      <div class="mt-3" v-html="workspace.description"></div>
      <div class="mt-3" v-if="workspace.createdBy">
        <span class="mb-0 mr-2 text-muted">{{ $t('Owner:') }}</span> <b>{{ workspace.createdBy.display_name }}</b>
      </div>
    </b-card-body>
  </b-card>
</template>

<script>
import {createNamespacedHelpers} from "vuex";

const {mapState, mapActions} = createNamespacedHelpers('workspace');

export default {
  name: "WorkspaceAbout",
  computed: {
    ...mapState({
      workspace: state => state.view.workspace
    })
  },
  methods: {
    ...mapActions(['showWorkspaceModal', 'deleteWorkspace']),
    onEditClick() {
      this.showWorkspaceModal(this.workspace);
    },
    async onDeleteClick() {
      let success = await this.$confirm(this.$i18n.t("If you delete this workspace all the timeline records and users will be removed from this workspace. Are you sure you want to continue?"));
      if (success) {
        let response = await this.deleteWorkspace(this.workspace);
        if (response.success) {
          this.$toast(this.$i18n.t("Workspace was successfully deleted"));
          this.$router.push('/')
        } else {
          this.$toast(response.body.message, 'danger');
        }
      }
    }
  }
}
</script>

<style scoped lang="scss">
</style>