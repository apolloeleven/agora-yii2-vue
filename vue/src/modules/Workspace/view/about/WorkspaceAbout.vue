<template>
  <b-card no-body class="p-3">
    <h4>{{ workspace.name }}</h4>
    <div class="mt-3" v-html="workspace.description"></div>
    <div class="mt-3" v-if="workspace.createdBy">
      <span class="mb-0 mr-2 text-muted">{{ $t('Owner:') }}</span> <b>{{ workspace.createdBy.displayName }}</b>
    </div>
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
  },
  mounted() {
    console.log(this.workspace);
  }
}
</script>

<style scoped>

</style>