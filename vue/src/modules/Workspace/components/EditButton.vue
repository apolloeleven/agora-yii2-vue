<template>
  <b-button v-if="tag === 'button'" size="sm" @click="onEditClick" variant="outline-primary">
    <i class="fas fa-pencil-alt"></i>
    {{ $t('Edit') }}
  </b-button>
  <b-dropdown-item v-else @click="onEditClick">
    <i class="fas fa-pencil-alt"></i>
    {{ $t('Edit') }}
  </b-dropdown-item>
</template>

<script>
import {createNamespacedHelpers} from "vuex";

const {mapActions} = createNamespacedHelpers('workspace');
const {mapActions: mapArticleActions} = createNamespacedHelpers('article');

export default {
  name: "EditButton",
  props: {
    model: Object,
    tag: String,
    type: String
  },
  methods: {
    ...mapActions(['showWorkspaceModal']),
    ...mapArticleActions(['showArticleModal']),
    onEditClick() {
      if (this.type === 'workspace') {
        this.showWorkspaceModal(this.model)
      } else if (this.type === 'folder') {
        this.showArticleModal({isArticle: false, article: this.model})
      } else {
        this.showArticleModal({isArticle: true, article: this.model})
      }
    }
  },
}
</script>

<style scoped>

</style>