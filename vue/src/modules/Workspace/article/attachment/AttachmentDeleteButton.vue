<template>
  <b-button v-if="tag === 'button'" variant="outline-danger" @click="onDeleteClick">
    <i class="far fa-trash-alt"></i>
    {{ $t('Delete') }}
  </b-button>
  <b-dropdown-item v-else @click="onDeleteClick">
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
    fileIds: Array,
    tag: String,
    model: Object,
  },
  methods: {
    ...mapActions(['deleteAttachments']),
    async onDeleteClick() {
      const result = await this.$confirm(
        this.$t(`Are you sure you want to delete {count} attachment(s)?`, {count: this.fileIds.length}),
        this.$t('This operation can not be undone')
      );
      if (result) {
        const data = {
          article_id: this.model.id,
          fileIds: this.fileIds
        };
        const res = await this.deleteAttachments(data);
        if (res.success) {
          this.$toast(this.$t(`{count} attachment(s) were successfully deleted`, {count: this.fileIds.length}));
        } else {
          this.$toast(res.body.message, 'danger');
        }
      }
    }
  },
}
</script>

<style scoped>

</style>