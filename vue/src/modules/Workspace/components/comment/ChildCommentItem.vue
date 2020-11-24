<template>
  <b-media class="py-2 mt-2">
    <template v-slot:aside>
      <b-img rounded="0" :src="'/assets/img/avatar.svg'" width="32" height="32" :alt="comment.createdBy.displayName"/>
    </template>
    <h6 class="mt-0 mb-0">
      <span style="color: #008BCA">{{ comment.createdBy.displayName }}</span>
      &nbsp;<i class="far fa-clock"/>
      {{ comment.updated_at | relativeDate }}&nbsp;
    </h6>
    <p class="mb-0">
      <span class="comment-wrapper" v-html="comment.comment" v-if="!showEditComments"/>
      <EditComment v-else :comment="comment" :show-edit="showEditComments" @updateComment="updateComment"/>
    </p>
    <b-button class="edit-comment" variant="link" :pressed.sync="showEditComments">
      <i class="fas fa-pencil-alt text-primary"/>
    </b-button>
    <DeleteComment :comment="comment"/>
  </b-media>
</template>

<script>
import DeleteComment from "./DeleteComment";
import EditComment from "./EditComment";

export default {
  name: "ChildCommentItem",
  components: {EditComment, DeleteComment},
  props: {
    comment: Object,
    index: Number
  },
  data() {
    return {
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