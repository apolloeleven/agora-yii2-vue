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
import {EDIT_TIMELINE, eventBus} from "../../../core/services/event-bus";

const {mapActions} = createNamespacedHelpers('workspace');
const {mapActions: mapArticleActions} = createNamespacedHelpers('article');
const {mapActions: mapTimelineActions} = createNamespacedHelpers('workspace');

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
    ...mapTimelineActions(['showTimelineModal']),
    onEditClick() {
      if (this.type === 'workspace') {
        this.showWorkspaceModal(this.model)
      } else if (this.type === 'folder') {
        this.showArticleModal({isArticle: false, article: this.model})
      } else if (this.type === 'article') {
        this.showArticleModal({isArticle: true, article: this.model})
      } else if (this.type === 'timeline') {
        if (this.model.action === null) {
          this.showTimelineModal(this.model)
        } else {
          eventBus.$emit(EDIT_TIMELINE, {
            model: this.model
          });
        }
      }
    }
  },
}
</script>

<style scoped>

</style>