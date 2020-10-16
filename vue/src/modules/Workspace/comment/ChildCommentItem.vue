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
    <DeleteComment :comment="comment"/>
  </b-media>
</template>

<script>
import DeleteComment from "./DeleteComment";

export default {
  name: "ChildCommentItem",
  components: {DeleteComment},
  props: {
    comment: Object,
    index: Number
  },
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

.comment-wrapper {
  font-weight: 400;

  & /deep/ img {
    width: 24px;
    margin: 0 1px;
  }
}
</style>