<template>
  <b-form @submit.prevent="onEdit" v-show="showEdit">
    <b-input-group class="mt-2">
      <b-form-textarea v-model="comment.comment"/>
      <b-input-group-append>
        <b-button @click="onEdit" variant="info">{{ $t('Comment') }}</b-button>
      </b-input-group-append>
    </b-input-group>
  </b-form>
</template>

<script>
import {createNamespacedHelpers} from "vuex";

const {mapActions: mapWorkspaceActions} = createNamespacedHelpers('workspace')

export default {
  name: "EditComment",
  props: {
    comment: Object,
    showEdit: Boolean,
  },
  methods: {
    ...mapWorkspaceActions(['editComment']),
    async onEdit() {
      if (this.comment.comment.replace(/\s/g, '') === '') {
        return this.$toast(this.$t(`Comment can not be blank`), 'danger');
      }
      const {success} = await this.editComment(this.comment);
      if (success) {
        this.$emit('updateComment');
      } else {
        this.$toast(this.$t(`Unable to edit comment`), 'danger');
      }
    },
  },
}
</script>

<style scoped>
.edit-comment {
  position: absolute;
  right: 30px;
  top: 10px;
  color: #495057;
}
</style>