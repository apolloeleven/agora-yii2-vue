<template>
  <b-card no-body class="mb-3" :style="'animation-delay: '+(index / 5)+'s'">
    <b-card-body class="pr-5 pb-0">
      <b-media>
        <template v-slot:aside>
          <b-img
            rounded="circle" :src="timeline.createdBy.image_url  || '/assets/img/avatar.svg'" width="48" height="48">
          </b-img>
        </template>
        <h5 class="mt-0">
          {{ timeline.createdBy.displayName }}

          <span v-if="timeline.action === SHARE_ARTICLE && timeline.article">
            {{ $t('created article') }}
            <router-link :to="{name: 'article.view', params: {id: timeline.article.id}}">
              {{ timeline.article.title }}
            </router-link>
          </span>
          <span v-else-if="timeline.action === SHARE_FILE && timeline.article">
                {{ $t('uploaded attachment(s) to article') }}
            <router-link :to="{name: 'article.view', params: {id: timeline.article.id}}">
              {{ timeline.article.title }}
            </router-link>
          </span>
        </h5>
        <p class="mb-0">
          <i class="far fa-clock"/>
          {{ timeline.updated_at | relativeDate }}
        </p>
      </b-media>
    </b-card-body>

    <div class="p-3 description" v-html="timeline.description"></div>
    <b-card-body v-if="timeline.action === this.SHARE_ARTICLE && timeline.article">
      <div class="row">
        <div class="col">
          <b-card-img :src="timeline.article.image_path || ''" class="rounded-0"/>
        </div>
        <div class="col border-left">
          <b-card-text text-tag="div" class="short-description" v-html="timeline.article.short_description"/>
        </div>
      </div>
    </b-card-body>
    <div v-if="timeline.file_url" class="timeline-preview">
      <div v-if="timeline.file_url.original && isImage(timeline.file_url.original)" class="image-preview">
        <b-img :src="timeline.file_url.converted" @error="loadOriginalImage" @click="previewModal" class="img-fluid" style="cursor: pointer"/>
      </div>
      <video v-else-if="timeline.file_url.original && isVideo(timeline.file_url.original)" controls class="video-preview">
        <source :src="timeline.file_url.original">
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
    <dropdown-buttons :item="timeline" @editClicked="onEditClicked" @removeClicked="onRemoveClicked"/>
  </b-card>
</template>

<script>
import fileService from '@/core/services/fileService';
import {createNamespacedHelpers} from "vuex";
import {SHARE_ARTICLE, SHARE_FILE} from "@/core/services/event-bus";
import CommentItem from "@/modules/Workspace/components/comment/CommentItem";
import AddComment from "@/modules/Workspace/components/comment/AddComment";
import LikeUnlikeButton from "@/core/components/LikeUnlikeButton";
import DropdownButtons from "@/core/components/DropdownButtons";

const {mapState} = createNamespacedHelpers('user');
const {mapActions: mapWorkspaceActions} = createNamespacedHelpers('workspace');

export default {
  name: "TimelineItem",
  components: {DropdownButtons, LikeUnlikeButton, AddComment, CommentItem},
  props: {
    index: Number,
    timeline: Object
  },
  data() {
    return {
      SHARE_ARTICLE: SHARE_ARTICLE,
      SHARE_FILE: SHARE_FILE,
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
    ...mapWorkspaceActions(['showTimelineModal', 'deleteTimelinePost', 'like', 'unlike', 'showPreviewModal']),
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
    previewModal() {
      this.showPreviewModal({
        activeFile: 0,
        files: [{file_path: this.timeline.file_url.original, mime: 'image/png', name: 'Uploaded image'}]
      });
    },
    loadOriginalImage() {
      this.timeline.file_url.converted = this.timeline.file_url.original
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