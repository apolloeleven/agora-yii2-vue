<template>
  <b-card no-body class="mb-3" :style="'animation-delay: '+(index / 5)+'s'">
    <b-card-body class="pr-5">
      <b-media>
        <template v-slot:aside>
          <b-img rounded="0" :src="timeline.createdBy.image_url  || '/assets/logo.png'" width="48" height="48"/>
        </template>
        <h5 class="mt-0">
          {{ timeline.createdBy.display_name }}
        </h5>
        <p class="mb-0">
          <i class="far fa-clock"/>
          {{ timeline.updated_at | relativeDate }}
        </p>
      </b-media>
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
    <dropdown-button :model="timeline" type="timeline" :permissionForEdit="isAllowed"/>
  </b-card>
</template>

<script>
import DropdownButton from "../Workspace/components/DropdownButton";
import fileService from '../../core/services/fileService';
import {createNamespacedHelpers} from "vuex";

const {mapState} = createNamespacedHelpers('user');

export default {
  name: "TimelineItem",
  components: {DropdownButton},
  props: {
    index: Number,
    timeline: Object
  },
  data() {
    return {}
  },
  computed: {
    ...mapState({
      user: state => state.currentUser.data
    }),
    isAllowed() {
      return this.user.id !== this.timeline.createdBy.id;
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