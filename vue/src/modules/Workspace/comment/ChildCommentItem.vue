<template>
  <b-media class="py-2">
    <template v-slot:aside>
      <b-img rounded="0" :src="'/assets/logo.png'" width="32" height="32" :alt="comment.createdBy.displayName"/>
    </template>
    <h6 class="mt-0 mb-0">
      <span style="color: #008BCA">{{ comment.createdBy.displayName }}</span>
      &nbsp;<span class="comment-wrapper" v-html="comment.comment"/>
    </h6>
    <p class="mb-0">
      <i class="far fa-clock"/>
      {{ comment.updated_at | relativeDate }}
    </p>
    <b-button @click="onDelete" class="delete-comment" variant="link">
      <i class="far fa-trash-alt"/>
    </b-button>
  </b-media>
</template>

<script>
import {createNamespacedHelpers} from "vuex";

const {mapActions} = createNamespacedHelpers('article')

export default {
  name: "ChildCommentItem",
  props: {
    comment: Object,
    index: Number
  },
  methods: {
    ...mapActions(['deleteComment']),
    async onDelete() {
      const {success} = await this.deleteComment(this.comment);
      if (!success) {
        this.$toast(this.$t(`Unable to delete comment`), 'danger');
      }
    },
  }
}
</script>

<style lang="scss" scoped>
.media {
  position: relative;
  border-bottom: 1px solid #f1f1f1;

  &:last-child {
    border-bottom: none;
  }
}

.delete-comment {
  position: absolute;
  right: 10px;
  top: 10px;
  color: #495057;
}

.comment-wrapper {
  font-weight: 400;

  & /deep/ img {
    width: 24px;
    margin: 0 1px;
  }
}
</style>