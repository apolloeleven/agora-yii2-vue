<template>
  <div v-if="loading">
    <content-spinner show/>
  </div>
  <div v-else class="row">
    <div class="col-md-12">
      <div class="row">
        <div class="col-md-12 text-right pb-3">
          <b-button @click="showTimelineForm" size="sm" variant="outline-primary">
            <i class="fas fa-plus-circle"/>
            {{ $t('Write on timeline') }}
          </b-button>
        </div>
      </div>
      <div class="timeline-records">
        <no-data :model="timelineData" :loading="loading" :text="$t('Nothing is shared on timeline')"></no-data>
        <template v-if="!loading">
          <TimelineItem v-for="(timeline, index) in timelineData" :timeline="timeline"
                        :index="index" :key="`timeline-post-${timeline.id}`"/>
        </template>
      </div>
    </div>
  </div>
</template>

<script>
import ContentSpinner from "@/core/components/ContentSpinner";
import NoData from "@/core/components/NoData";
import TimelineItem from "@/modules/Workspace/view/timeline/TimelineItem";
import {createNamespacedHelpers} from "vuex";

const {mapActions: mapTimelineActions, mapState: mapTimelineState} = createNamespacedHelpers('workspace');

export default {
  name: "WorkspaceTimeline",
  components: {TimelineItem, NoData, ContentSpinner},
  computed: {
    ...mapTimelineState({
      timelineData: state => state.view.timeline.data,
      loading: state => state.view.timeline.loading,
    }),
  },
  methods: {
    ...mapTimelineActions(['showTimelineModal', 'getTimelinePosts']),
    showTimelineForm() {
      this.showTimelineModal(null);
    },
  },
  mounted() {
    this.getTimelinePosts(this.$route.params.id);
  },
}
</script>

<style scoped>

</style>