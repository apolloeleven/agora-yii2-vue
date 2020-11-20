<template>
  <div class="card">
    {{activities.length}}
    <div v-for="(activity, index) in activities">
      <div>
        {{activity.action}}
        {{index}}
      </div>
    </div>
  </div>
</template>

<script>

import {createNamespacedHelpers} from "vuex";

const {mapState, mapActions} = createNamespacedHelpers('workspace');

export default {
  name: "WorkspaceActivity",
  computed: {
    ...mapState({
      workspace: state => state.view.workspace,
      activities: state => state.view.activity.data
    })
  },
  methods: {
    ...mapActions(['getActivities']),
  },
  beforeMount() {
    this.getActivities(this.workspace.id);
  }
}
</script>

<style scoped>

</style>