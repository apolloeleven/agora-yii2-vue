<template>
  <b-card no-body class="mb-3" :style="'animation-delay: '+(index / 5)+'s'">
    <b-card-header v-if="!timeline.poll_id" class="d-flex justify-content-between">
      <b-media>
        <template v-slot:aside>
          <b-img
            rounded="circle" :src="timeline.createdBy.image_url  || '/assets/img/avatar.svg'" width="46" height="46">
          </b-img>
        </template>
        <h5 class="mt-0">{{ timeline.createdBy.displayName }}</h5>
        <p class="mb-0">
          <i class="far fa-clock"/>
          {{ timeline.updated_at | relativeDate }}
        </p>
      </b-media>
      <div v-if="!timeline.poll_id" class="ml-auto">
        <i class="mr-3 fas fa-pencil-alt text-primary hover-pointer" @click="onEditClicked"/>
        <i class="far fa-trash-alt text-danger hover-pointer" @click="onRemoveClicked"/>
      </div>
    </b-card-header>
    <div v-if="!timeline.poll_id" class="p-3 description" v-html="timeline.description"/>

    <PollItem
      v-else :item="timeline.poll" @onDeleteClick="onDeleteClicked(timeline.poll)" @onVoteClick="onVoteClicked">
    </PollItem>

    <div v-if="timeline.file_url" class="timeline-preview">
      <div v-if="isImage(timeline.file_url)" class="image-preview">
        <b-img :src="timeline.file_url" class="img-fluid" style="cursor: pointer"/>
      </div>
      <video v-else-if="isVideo(timeline.file_url)" controls class="video-preview">
        <source :src="timeline.file_url">
      </video>
    </div>
    <b-card-footer>
      <LikeUnlikeButton class="mr-2" :item="timeline.userLikes" :liked="liked" @onLikeClicked="onLikeClicked"/>
      <b-button size="sm" pill variant="light" :pressed.sync="showComments">
        <i class="far fa-comments fa-lg"/>
        <b-badge v-if="timeline.timelineComments" class="ml-2" pill variant="secondary">
          {{ timeline.timelineComments.length }}
        </b-badge>
      </b-button>
    </b-card-footer>
    <AddComment :timeline_id="timeline.id" v-if="showComments" :currentUser="user"/>
    <b-card-body v-if="showComments && timeline.timelineComments && timeline.timelineComments.length" class="pt-1 pb-1">
      <CommentItem
        v-for="(comment, index) in timeline.timelineComments" :comment="comment" :index="index"
        :key="`timeline-item-comment-${index}`" :currentUser="user">
      </CommentItem>
    </b-card-body>
  </b-card>
</template>

<script>
import fileService from '@/core/services/fileService';
import {createNamespacedHelpers} from "vuex";
import CommentItem from "@/modules/Workspace/components/comment/CommentItem";
import AddComment from "@/modules/Workspace/components/comment/AddComment";
import LikeUnlikeButton from "@/core/components/LikeUnlikeButton";
import PollItem from "../polls/PollItem";

const {mapState} = createNamespacedHelpers('user');
const {mapActions: mapWorkspaceActions} = createNamespacedHelpers('workspace');

export default {
  name: "TimelineItem",
  components: {PollItem, LikeUnlikeButton, AddComment, CommentItem},
  props: {
    index: Number,
    timeline: Object
  },
  data() {
    return {
      showComments: true,
    }
  },
  computed: {
    ...mapState({
      user: state => state.currentUser.data
    }),
    isAllowed() {
      return this.user.id === this.timeline.createdBy.id;
    },
    liked() {
      return !!(this.timeline.myLikes && this.timeline.myLikes.length > 0);
    }
  },
  methods: {
    ...mapWorkspaceActions(['showTimelineModal', 'deleteTimelinePost', 'like', 'unlike', 'deletePoll', 'addVote']),
    isImage(url) {
      return fileService.isImage(url)
    },
    isVideo(url) {
      return fileService.isVideo(url)
    },
    onEditClicked() {
      this.showTimelineModal(this.timeline);
    },
    async onRemoveClicked() {
      const result = await this.$confirm(this.$t('All timeline records and attachments will be deleted from this timeline. Are you sure you want to continue?'),
        this.$t('This operation can not be undone'))
      if (result) {
        const res = await this.deleteTimelinePost(this.timeline);
        if (res.success) {
          this.$toast(this.$t(`The timeline item was successfully deleted`));
        } else {
          this.$toast(res.body.message, 'danger');
        }
      }
    },
    async onLikeClicked() {
      const params = {}
      params.timeline_post_id = this.timeline.id;

      if (this.liked) {
        params.id = this.timeline.myLikes[0].id;
        await this.unlike(params);
      } else {
        await this.like(params);
      }
    },
    async onDeleteClicked(item) {
      const result = await this.$confirm(
        this.$t(`Are you sure you want to delete this poll?`),
        this.$t('This operation can not be undone')
      );
      if (result) {
        const {success, body} = await this.deletePoll(item);
        if (success) {
          this.$toast(this.$t(`Poll deleted successfully`));
        } else {
          this.$toast(body, 'danger');
        }
      }
    },
    async onVoteClicked({selected, item}) {
      const {success, body} = await this.addVote({selected, item});
      if (success) {
        this.$toast(this.$t(`Vote added successfully`));
      } else {
        this.$toast(body, 'danger');
      }
    }
  },
}
</script>

<style lang="scss" scoped>
.description {
  & /deep/ img {
    max-width: 100%;
  }

  & /deep/ p:last-of-type {
    margin-bottom: 0;
  }
}

.video-preview {
  max-width: 100%;
}

.image-preview {
  position: relative;
  //width: 300px;
  //height: 200px;
  margin: 0 auto;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;

  img {
    max-width: 100%;
    max-height: 100%;
    width: auto;
  }
}

</style>