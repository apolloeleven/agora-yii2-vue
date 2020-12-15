<template>
  <b-card no-body class="workspace-about shrinked-width">
    <b-card-header class="d-flex align-items-center">
      <h5 class="mb-0">{{ $t('Workspace Details') }}</h5>
      <b-button size="sm" variant="primary" class="ml-auto">
        <i class="fas fa-edit"/>
        {{ $t('Edit') }}
      </b-button>
      <b-button size="sm" variant="danger" class="ml-2">
        <i class="fas fa-delete"/>
        {{ $t('Delete') }}
      </b-button>
    </b-card-header>
    <b-card-body>
      <h4>{{ workspace.name }}</h4>
      <div class="mt-3" v-html="workspace.description"></div>
      <div class="mt-3" v-if="workspace.createdBy">
        <span class="mb-0 mr-2 text-muted">{{ $t('Owner:') }}</span> <b>{{ workspace.createdBy.displayName }}</b>
      </div>
    </b-card-body>
  </b-card>
</template>

<script>
import {createNamespacedHelpers} from "vuex";

const {mapState} = createNamespacedHelpers('workspace');

export default {
  name: "WorkspaceAbout",
  computed: {
    ...mapState({
      workspace: state => state.view.workspace
    })
  }
}
</script>

<style scoped lang="scss">
</style>