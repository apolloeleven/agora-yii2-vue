<template>
  <b-media class="py-2">
    <template v-slot:aside>
      <b-img rounded="0" :src="'/assets/img/avatar.svg'" width="32" height="32" :alt="comment.createdBy.displayName"/>
    </template>
    <h6 class="mt-0 mb-0">
      <span style="color: #008BCA">{{ comment.createdBy.displayName }}</span>
      &nbsp;<i class="far fa-clock"/>
      {{ comment.updated_at | relativeDate }}&nbsp;
      <b-button size="sm" pill variant="light" :pressed.sync="showComments">
        <i class="fas fa-reply fa-lg"/>
        <b-badge class="ml-2" pill variant="secondary">
          {{ comment.childrenComments.length }}
        </b-badge>
      </b-button>
    </h6>
    <p class="mb-0">
      <span class="comment-wrapper" v-html="comment.comment" v-if="!showEditComments"/>
      <EditComment v-else :comment="comment" :show-edit="showEditComments" @updateComment="updateComment"/>
    </p>
    <b-button class="edit-comment" variant="link" :pressed.sync="showEditComments">
      <i class="fas fa-pencil-alt text-primary"/>
    </b-button>
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
import EditComment from "./EditComment";

export default {
  name: "CommentItem",
  components: {EditComment, DeleteComment, ChildCommentItem, AddComment},
  props: {
    comment: Object,
    index: Number
  },
  data() {
    return {
      showComments: false,
      showEditComments: false,
    }
  },
  methods: {
    updateComment() {
      this.showEditComments = false;
    },
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

.edit-comment {
  position: absolute;
  right: 35px;
  top: 0;
  color: #495057;
}
</style>