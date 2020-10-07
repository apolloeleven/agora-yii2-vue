<template>
  <b-button v-if="tag === 'button'" variant="outline-danger" @click="onDeleteClick(file)">
    <i class="far fa-trash-alt"></i>
    {{ $t('Delete') }}
  </b-button>
  <b-dropdown-item v-else @click="onDeleteClick(file)">
    <i class="far fa-trash-alt"></i>
    {{ $t('Delete') }}
  </b-dropdown-item>
</template>

<script>

import {createNamespacedHelpers} from "vuex";

const {mapActions} = createNamespacedHelpers('article');

export default {
  name: "AttachmentDeleteButton",
  props: {
    file: Object,
    tag: String,
    currentArticle: Object,
  },
  methods: {
    ...mapActions(['deleteAttachments']),
    async onDeleteClick(file) {
      const fileIds = [file.id];
      const result = await this.$confirm(
        this.$t(`Are you sure you want to delete {count} attachment(s)?`, {count: fileIds.length}),
        this.$t('This operation can not be undone')
      );
      if (result) {
        const data = {
          article_id: this.currentArticle.id,
          files: fileIds
        };
        const res = await this.deleteAttachments(data);
        if (res.success) {
          this.$toast(this.$t(`{count} attachment(s) were successfully deleted`, {count: fileIds.length}));
        } else {
          this.$toast(this.$t(`{count} attachment(s) were not deleted`, {count: fileIds.length}), 'danger');
        }
      }
    }
  },
}
</script>

<style scoped>

</style>