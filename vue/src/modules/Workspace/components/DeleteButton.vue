<template>
  <b-button v-if="tag === 'button'" size="sm" variant="outline-danger" @click="onDeleteClick">
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
import FavouritesService from "./AddToFavourites/FavouritesService";

const {mapActions} = createNamespacedHelpers('workspace');
const {mapActions: mapArticleActions} = createNamespacedHelpers('article');
const {mapActions: mapTimelineActions} = createNamespacedHelpers('timeline');
const {mapActions: mapFileManagerActions} = createNamespacedHelpers('fileManager');

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
    ...mapTimelineActions(['deleteTimelinePost']),
    ...mapFileManagerActions(['deleteFolder']),
    async onDeleteClick() {
      if (this.type === 'workspace') {
        const result = await this.$confirm(this.$t('All users and timeline records will be removed from this workspace. Are you sure you want to continue?'),
          this.$t('This operation can not be undone'))
        if (result) {
          const res = await this.deleteWorkspace(this.model);
          if (res.success) {
            this.$toast(this.$t(`The workspace '{name}' was successfully deleted`, {name: this.model.name}));
          } else {
            this.$toast(res.body.message, 'danger');
          }
        }
      } else if (this.type === 'timeline') {
        const result = await this.$confirm(this.$t('All timeline records and attachments will be deleted from this timeline. Are you sure you want to continue?'),
          this.$t('This operation can not be undone'))
        if (result) {
          const res = await this.deleteTimelinePost(this.model);
          if (res.success) {
            this.$toast(this.$t(`The timeline item was successfully deleted`));
          } else {
            this.$toast(res.body.message, 'danger');
          }
        }
      } else if (this.type === 'folder') {
        const result = await this.$confirm(this.$t('All attachments and timeline records will be removed from this article. Are you sure you want to continue?'),
          this.$t('This operation can not be undone'))
        if (result) {
          const res = await this.deleteFolder(this.model);
          if (res.success) {
            this.$toast(this.$t(`The folder '{title}' was successfully deleted`, {title: this.model.name}));
          } else {
            this.$toast(res.body.message, 'danger');
          }
        }
      } else {
        const result = await this.$confirm(this.$t('All attachments and timeline records will be removed from this article. Are you sure you want to continue?'),
          this.$t('This operation can not be undone'))
        if (result) {
          const res = await this.deleteArticle(this.model);
          if (res.success) {
            this.$toast(this.$t(`The article '{title}' was successfully deleted`, {title: this.model.title}));
            let path = `/article/${this.model.id}`;
            if (FavouritesService.inFavourites(path)) {
              FavouritesService.removeFavourite(path)
            }
          } else {
            this.$toast(res.body.message, 'danger');
          }
        }
      }
    },
  },
}
</script>

<style scoped>

</style>