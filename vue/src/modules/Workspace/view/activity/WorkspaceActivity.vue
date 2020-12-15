<template>
  <div v-if="activities.loading">
    <content-spinner show/>
  </div>
  <div v-else class="workspace-activity shrinked-width">
    <no-data :model="activities.data" :loading="activities.loading"
             :text="$t('This workspace does not have any activity')"></no-data>
    <ul v-if="!activities.loading" class="activity">
      <ActivityItem v-for="(activity, index) in activities.data" v-bind:key="index" :activity="activity"/>
    </ul>
  </div>
</template>

<script>

import {createNamespacedHelpers} from "vuex";
import ContentSpinner from "@/core/components/ContentSpinner";
import ActivityItem from "@/modules/Workspace/view/activity/ActivityItem";
import NoData from "@/core/components/NoData";

const {mapState, mapActions} = createNamespacedHelpers('workspace');

export default {
  name: "WorkspaceActivity",
  components: {NoData, ActivityItem, ContentSpinner},
  computed: {
    ...mapState({
      workspace: state => state.view.workspace,
      activities: state => state.view.activity
    })
  },
  watch: {
    '$route.params.id': function (id) {
      this.getActivities(id);
    },
  },
  methods: {
    ...mapActions(['getActivities']),
  },
  beforeMount() {
    this.getActivities(this.$route.params.id);
  }
}
</script>

<style scoped lang="scss">
.workspace-activity {
  overflow: auto;
  height: 100%;
}

ul.activity {
  list-style-type: none;
  position: relative;

  &:before {
    content: ' ';
    background: #d4d9df;
    display: inline-block;
    position: absolute;
    left: 29px;
    width: 2px;
    height: 100%;
  }

  > li {
    margin: 10px 0;
    padding-left: 20px;

    &:before {
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
  }
}
</style>