<template>
  <b-button @click="onDelete" class="delete-comment" variant="link">
    <i class="far fa-trash-alt"/>
  </b-button>
</template>

<script>
import {createNamespacedHelpers} from "vuex";

const {mapActions} = createNamespacedHelpers('article')

export default {
  name: "DeleteComment",
  props: {
    comment: Object,
  },
  methods: {
    ...mapActions(['deleteComment']),
    async onDelete() {
      const {success} = await this.deleteComment(this.comment);
      if (!success) {
        this.$toast(this.$t(`Unable to delete comment`), 'danger');
      }
    },
  },
}
</script>

<style scoped>
.delete-comment {
  position: absolute;
  right: 10px;
  top: 10px;
  color: #495057;
}
</style>