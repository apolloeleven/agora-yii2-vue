<template>
  <b-media class="py-2">
    <template v-slot:aside>
      <b-img rounded="0" :src="'/assets/img/avatar.svg'" width="32" height="32" :alt="comment.createdBy.displayName"/>
    </template>
    <h6 class="mt-0 mb-0">
      <span style="color: #008BCA">{{ comment.createdBy.displayName }}</span>
      &nbsp;<span class="comment-wrapper" v-html="comment.comment"/>&nbsp;
      <b-button size="sm" pill variant="light" :pressed.sync="showComments">
        <i class="fas fa-reply fa-lg"/>
        <b-badge class="ml-2" pill variant="secondary">
          {{ comment.childrenComments.length }}
        </b-badge>
      </b-button>
    </h6>
    <p class="mb-0">
      <i class="far fa-clock"/>
      {{ comment.updated_at | relativeDate }}
    </p>
    <DeleteComment :comment="comment"/>
    <b-card-body v-if="showComments" class="pt-1 pb-1">
      <ChildCommentItem v-for="(com, index) in comment.childrenComments" :comment="com" :index="index"
                        :key="`comment-item-child-comment-${index}`"/>
    </b-card-body>
    <AddComment :parent_id="comment.id" v-if="showComments"/>
  </b-media>
</template>

<script>
import AddComment from "./AddComment";
import ChildCommentItem from "./ChildCommentItem";
import DeleteComment from "./DeleteComment";

export default {
  name: "CommentItem",
  components: {DeleteComment, ChildCommentItem, AddComment},
  props: {
    comment: Object,
    index: Number
  },
  data() {
    return {
      showComments: false
    }
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