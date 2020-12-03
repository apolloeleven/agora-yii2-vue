<template>
  <div v-if="loading">
    <content-spinner show/>
  </div>
  <div v-else class="workspace-timeline">
    <div class="card mb-3">
      <div class="card-header border-bottom-0 text-right">
        <b-button @click="showTimelineForm" size="sm" variant="primary">
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
    <FilePreviewModal @onDownloadClick="onDownloadClick"/>
  </div>
</template>

<script>
import ContentSpinner from "@/core/components/ContentSpinner";
import NoData from "@/core/components/NoData";
import TimelineItem from "@/modules/Workspace/view/timeline/TimelineItem";
import FilePreviewModal from "@/modules/Workspace/view/files/FilePreviewModal";
import {createNamespacedHelpers} from "vuex";

const {mapActions: mapTimelineActions, mapState: mapTimelineState} = createNamespacedHelpers('workspace');

export default {
  name: "WorkspaceTimeline",
  components: {TimelineItem, NoData, ContentSpinner, FilePreviewModal},
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
    onDownloadClick() {

    }
  },
  mounted() {
    this.getTimelinePosts(this.$route.params.id);
  },
}
</script>

<style lang="scss" scoped>
  .workspace-timeline {
    max-width: 680px;
    margin: 0 auto;
    overflow: auto;
    height: 100%;
  }
</style>