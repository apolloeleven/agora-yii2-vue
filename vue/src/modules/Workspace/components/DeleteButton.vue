<template>
  <b-button v-if="tag === 'button'" size="sm" variant="outline-danger" @click="onDeleteClick">
    <i class="fas fa-trash-alt"></i>
    {{ $t('Delete') }}
  </b-button>
  <b-dropdown-item v-else @click="onDeleteClick">
    <i class="fas fa-trash-alt"></i>
    {{ $t('Delete') }}
  </b-dropdown-item>
</template>

<script>
import {createNamespacedHelpers} from "vuex";

const {mapActions} = createNamespacedHelpers('workspace');
const {mapActions: mapArticleActions} = createNamespacedHelpers('article');

export default {
  name: "DeleteButton",
  props: {
    model: Object,
    tag: String,
    type: String
  },
  methods: {
    ...mapActions(['deleteWorkspace']),
    ...mapArticleActions(['deleteArticle']),
    async onDeleteClick() {
      if (this.type === 'workspace') {
        const result = await this.$confirm(this.$t('All users and timeline records will be removed from this workspace. Are you sure you want to continue?'),
          this.$t('This operation can not be undone'))
        if (result) {
          const res = await this.deleteWorkspace(this.model);
          if (res.success) {
            this.$toast(this.$t(`The workspace '{name}' was successfully deleted`, {name: this.model.name}));
          } else {
            this.$toast(this.$t(`Unable to delete workspace`), 'danger');
          }
        }
      } else {
        if (this.type === 'folder') {
          this.resource = 'folder';
        } else {
          this.resource = 'article';
        }

        const result = await this.$confirm(this.$t('All attachments and timeline records will be removed from this article. Are you sure you want to continue?'),
          this.$t('This operation can not be undone'))
        if (result) {
          const res = await this.deleteArticle(this.model);
          if (res.success) {
            this.$toast(this.$t(`The ${this.resource} '{title}' was successfully deleted`, {title: this.model.title}));
          } else {
            this.$toast(this.$t(`Unable to delete folder`), 'danger');
          }
        }
      }
    },
  },
}
</script>

<style scoped>

</style>