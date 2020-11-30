<template>
  <div v-if="activities.loading">
    <content-spinner show/>
  </div>
  <div v-else class="card">
    <ActivityItem v-for="(activity, index) in activities.data" v-bind:key="index" :activity="activity"/>
  </div>
</template>

<script>

import {createNamespacedHelpers} from "vuex";
import ContentSpinner from "@/core/components/ContentSpinner";
import ActivityItem from "@/modules/Workspace/view/activity/ActivityItem";

const {mapState, mapActions} = createNamespacedHelpers('workspace');

export default {
  name: "WorkspaceActivity",
  components: {ActivityItem, ContentSpinner},
  computed: {
    ...mapState({
      workspace: state => state.view.workspace,
      activities: state => state.view.activity
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