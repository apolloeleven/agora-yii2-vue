<template>
  <div v-if="loading && !this.timelineData">
    <content-spinner show/>
  </div>
  <div v-else class="workspace-timeline shrinked-width">
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
      <TimelineItem v-for="(timeline, index) in timelineData" :timeline="timeline"
                    :index="index" :key="`timeline-post-${timeline.id}`"/>
      <div v-if="loading && this.timelineData">
        <content-spinner show/>
      </div>
    </div>
  </div>
</template>

<script>
import ContentSpinner from "@/core/components/ContentSpinner";
import NoData from "@/core/components/NoData";
import TimelineItem from "@/modules/Workspace/view/timeline/TimelineItem";
import {createNamespacedHelpers} from "vuex";
import {eventBus} from "@/core/services/event-bus";

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
  data() {
    return {
      allLoaded: false,
      postsLimit: 20,
      lastPostId: 0,
    }
  },
  watch: {
    '$route.params.id': function (id) {
      this.allLoaded = false;
      this.loading = false;
      this.lastPostId = 0;
      this.timelinePosts(id);
    },
  },
  methods: {
    ...mapTimelineActions(['showTimelineModal', 'getTimelinePosts']),
    showTimelineForm() {
      this.showTimelineModal(null);
    },
    async timelinePosts(workspaceId) {
      if (this.allLoaded || this.loading) return;
      let res = await this.getTimelinePosts({
        workspace_id: workspaceId,
        posts_limit: this.postsLimit,
        last_post_id: this.lastPostId,
      });
      if (res.success) {
        if (res.body.length) {
          this.lastPostId = res.body[res.body.length - 1].id;
        }
        this.allLoaded = res.body.length < this.postsLimit;
      }
    },
    resetAndLoadArticles(workspaceId) {
      this.lastPostId = 0;
      this.allLoaded = false;
      this.timelinePosts(workspaceId);
    }
  },
  destroyed() {
    eventBus.$off('onScrollToBottom')
  },
  mounted() {
    let workspaceId = this.$route.params.id;
    this.resetAndLoadArticles(workspaceId);

    eventBus.$on('onScrollToBottom', () => {
      this.timelinePosts(workspaceId);
    })
  },
}
</script>

<style lang="scss" scoped>
.workspace-timeline {
  overflow: auto;
  height: 100%;
}
</style>