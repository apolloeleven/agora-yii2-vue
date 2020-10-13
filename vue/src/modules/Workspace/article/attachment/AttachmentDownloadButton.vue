<template>
  <b-button v-if="tag === 'button'" variant="outline-primary" @click="onDownloadClick">
    <i class="fas fa-download"/>
    {{ $t('Download') }}
  </b-button>
  <b-dropdown-item v-else @click="onDownloadClick">
    <i class="fas fa-download"/>
    {{ $t('Download') }}
  </b-dropdown-item>
</template>

<script>;
import authService from "../../../../core/services/authService";
import {AppSettings} from "../../../../shared/AppSettings";

export default {
  name: "AttachmentDownloadButton",
  props: {
    file: Object,
    tag: String
  },
  methods: {
    onDownloadClick() {
      window.location.href = `${AppSettings.url()}/v1/workspaces/article-file/download-attachment/${this.file.id}?access-token=${authService.getToken()}`;
    },
  }
}
</script>

<style scoped>

</style>