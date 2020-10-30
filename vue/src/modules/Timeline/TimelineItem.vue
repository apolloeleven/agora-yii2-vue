<template>
  <b-card no-body class="mb-3" :style="'animation-delay: '+(index / 5)+'s'">
    <b-card-body class="pr-5">
      <b-media>
        <template v-slot:aside>
          <b-img rounded="0" :src="timeline.createdBy.image_url  || '/assets/logo.png'" width="48" height="48"/>
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
      <div v-if="isImage(timeline.file_url)" class="image-preview">
        <b-img :src="timeline.file_url" class="img-fluid" style="cursor: pointer"/>
      </div>
      <video v-else-if="isVideo(timeline.file_url)" controls class="video-preview">
        <source :src="timeline.file_url">
      </video>
    </div>
    <div class="row">
      <div class="col">
        <div class="p-3 description" v-html="timeline.description"></div>
      </div>
    </div>
    <b-card-footer>
      <b-button size="sm" pill variant="light" :pressed.sync="showComments">
        <i class="far fa-comments fa-lg"/>
        <b-badge v-if="timeline.timelineComments" class="ml-2" pill variant="secondary">
          {{ timeline.timelineComments.length }}
        </b-badge>
      </b-button>
    </b-card-footer>
    <AddComment :timeline_id="timeline.id" v-if="showComments"/>
    <b-card-body v-if="showComments && timeline.timelineComments && timeline.timelineComments.length">
      <CommentItem
        v-for="(comment, index) in timeline.timelineComments" :comment="comment" :index="index" :key="index">
      </CommentItem>
    </b-card-body>
    <dropdown-button :model="timeline" type="timeline" :permissionForEdit="isAllowed"/>
  </b-card>
</template>

<script>
import DropdownButton from "../Workspace/components/DropdownButton";
import fileService from '../../core/services/fileService';
import {createNamespacedHelpers} from "vuex";
import {SHARE_ARTICLE, SHARE_FILE} from "../../core/services/event-bus";
import CommentItem from "../Workspace/comment/CommentItem";
import AddComment from "../Workspace/comment/AddComment";

const {mapState} = createNamespacedHelpers('user');

export default {
  name: "TimelineItem",
  components: {AddComment, CommentItem, DropdownButton},
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
  },
  methods: {
    isImage(url) {
      return fileService.isImage(url)
    },
    isVideo(url) {
      return fileService.isVideo(url)
    },
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
  width: 300px;
  height: 200px;
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