<template>
  <div v-if="activities.loading">
    <content-spinner show/>
  </div>
  <div v-else class="row">
    <div class="workspace-activity">
      <ul class="activity">
        <ActivityItem v-for="(activity, index) in activities.data" v-bind:key="index" :activity="activity"/>
      </ul>
    </div>
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
.workspace-activity {
  min-width: 660px;
  margin: 0 auto;
  overflow: auto;
  height: 100%;
}
ul.activity {
  list-style-type: none;
  position: relative;
}
ul.activity:before {
  content: ' ';
  background: #d4d9df;
  display: inline-block;
  position: absolute;
  left: 29px;
  width: 2px;
  height: 100%;
}
ul.activity > li {
  margin: 20px 0;
  padding-left: 20px;
}
ul.activity > li:before {
  content: ' ';
  background: white;
  display: inline-block;
  position: absolute;
  border-radius: 50%;
  border: 3px solid #3989c6;
  left: 20px;
  width: 20px;
  height: 20px;
}
</style>