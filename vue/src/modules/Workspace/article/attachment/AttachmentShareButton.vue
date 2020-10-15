<template>
  <b-button v-if="tag === 'button'" variant="outline-dark" @click="onShareClick">
    <i class="fas fa-share"/>
    {{ $t('Share') }}
    <b-badge class="ml-2" pill variant="secondary"/>
  </b-button>
  <b-dropdown-item v-else-if="tag === 'dropdown'" @click="onShareClick">
    <i class="fas fa-share"/>
    {{ $t('Share') }}
    <b-badge class="ml-2" pill variant="secondary"/>
  </b-dropdown-item>
  <b-button v-else-if="tag === 'smallIcon'" size="sm" pill variant="light" @click="onShareClick">
    <i class="fas fa-share fa-lg"/>
    <!--<b-badge class="ml-2" pill variant="secondary">
      {{article.share_count}}
    </b-badge>-->
  </b-button>
  <b-button v-else  @click="onShareClick">
    <i class="fas fa-share"/>
    {{ $t('Share') }}
  </b-button>
</template>

<script>
import {eventBus, SHARE_TO_TIMELINE, SHARE_FILE, SHARE_ARTICLE} from "../../../../core/services/event-bus";

export default {
  name: "AttachmentShareButton",
  props: {
    fileIds: Array,
    tag: String,
    model: Object,
    modalType: String,
  },
  computed: {
    shareType() {
      if (this.modalType === 'article') {
        return SHARE_ARTICLE
      } else {
        return SHARE_FILE
      }
    }
  },
  methods: {
    onShareClick() {
      eventBus.$emit(SHARE_TO_TIMELINE, {
        type: this.shareType,
        articleModel: this.model,
        selectedIds: this.fileIds,
      });
    }
  },
}
</script>

<style scoped>

</style>