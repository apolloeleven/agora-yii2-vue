<template>
  <div v-if="loading && lastPostId === 0">
    <content-spinner show/>
  </div>
  <div v-else ref="postsContent" class="workspace-timeline shrinked-width" @scroll="onScroll">
    <b-card class="card mb-3">
      <b-media class="align-items-center">
        <template #aside>
          <b-img class="user-avatar mr-2" width="48" :src="currentUser.data.image_url || '/assets/img/avatar.svg'"
                 rounded="circle"
                 :alt="currentUser.display_name"/>
        </template>

        <b-button @click="showTimelineForm" size="lg" variant="light" class="write-on-timeline w-100 text-left">
          {{ $t('Write something on timeline') }}
        </b-button>
      </b-media>
      <hr>
      <b-button block @click="showTimelineForm">
        <i class="fas fa-photo-video"></i>
        {{ $t('Photo/Video') }}
      </b-button>
      <!--      <div class="card-header border-bottom-0 text-right">-->
      <!--        -->
      <!--      </div>-->
    </b-card>
    <div class="timeline-records">
      <no-data :model="timelineData" :loading="loading" :text="$t('Nothing is shared on timeline')"></no-data>
      <TimelineItem v-for="(timeline, index) in timelineData"
                    :timeline="timeline"
                    :workspace="workspace"
                    :index="index" :key="`timeline-post-${timeline.id}`"/>
      <div v-if="loading && lastPostId !== 0">
        <content-spinner show/>
      </div>
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
import {eventBus} from "@/core/services/event-bus";
import {AppSettings} from "@/shared/AppSettings";
import authService from "@/core/services/authService";

const {mapActions: mapTimelineActions, mapState: mapTimelineState} = createNamespacedHelpers('workspace');
const {mapState} = createNamespacedHelpers('user');

export default {
  name: "WorkspaceTimeline",
  components: {TimelineItem, NoData, ContentSpinner, FilePreviewModal},
  props: {
    workspaceId: {
      type: Number,
      default: null,
    },
    workspace: Object
  },
  computed: {
    ...mapState(['currentUser']),
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
      this.lastPostId = 0;
      this.timelinePosts(id);
    },
  },
  methods: {
    ...mapTimelineActions(['showTimelineModal', 'getTimelinePosts']),
    onScroll() {
      if (this.$refs.postsContent.scrollTop + this.$refs.postsContent.offsetHeight >= this.$refs.postsContent.scrollHeight) {
        eventBus.$emit('onScrollToBottom')
      }
    },
    showTimelineForm() {
      this.showTimelineModal({showWorkspaceField: !this.workspace});
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
    },
    onDownloadClick(e) {
      window.location.href = `${AppSettings.url()}/v1/workspaces/folder/download-file/${e.id}?access-token=${authService.getToken()}`;
    },
  },
  destroyed() {
    eventBus.$off('onScrollToBottom')
  },
  mounted() {
    let workspaceId = this.$route.params.id ? this.$route.params.id : this.workspaceId;
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

.write-on-timeline {
  flex: 1;
}
</style>