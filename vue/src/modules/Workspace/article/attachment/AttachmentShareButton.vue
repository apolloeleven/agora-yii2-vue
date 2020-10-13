<template>
  <b-button v-if="tag === 'button'" @click="onShareClick">
    <i class="fas fa-share"/>
    {{ $t('Share') }}
  </b-button>
  <b-dropdown-item v-else-if="tag === 'dropdown'" @click="onShareClick">
    <i class="fas fa-share"/>
    {{ $t('Share') }}
    <b-badge class="ml-2" pill variant="secondary"/>
  </b-dropdown-item>
  <b-button v-else variant="outline-dark" @click="onShareClick">
    <i class="fas fa-share"/>
    {{ $t('Share') }}
    <b-badge class="ml-2" pill variant="secondary"/>
  </b-button>
</template>

<script>
import {eventBus, SHARE_TO_TIMELINE, SHARE_FILE} from "../../../../core/services/event-bus";

export default {
  name: "AttachmentShareButton",
  props: {
    fileIds: Array,
    tag: String,
    model: Object,
  },
  methods: {
    onShareClick() {
      eventBus.$emit(SHARE_TO_TIMELINE, {
        type: SHARE_FILE,
        articleModel: this.model,
        data: this.fileIds
      });
    }
  },
}
</script>

<style scoped>

</style>