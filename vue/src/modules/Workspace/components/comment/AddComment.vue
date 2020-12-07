<template>
  <b-card-body class="pt-1" :class="!parent_id ? 'bg-light' : ''">
    <b-media>
      <template v-slot:aside>
        <b-img rounded="circle" :src="currentUser.image_url  || '/assets/img/avatar.svg'" width="32" height="32"/>
      </template>
      <b-form @submit.prevent="onAdd">
        <b-input-group>
          <b-form-input v-model="model.comment" :placeholder="parent_id ? $t('Write a replay') : $t('Leave comment')"/>
          <b-input-group-append>
            <b-button @click="onAdd" variant="info">{{ $t('Comment') }}</b-button>
          </b-input-group-append>
        </b-input-group>
      </b-form>
    </b-media>
  </b-card-body>
</template>

<script>
import AddCommentModel from "./AddCommentModel";
import {createNamespacedHelpers} from "vuex";

const {mapActions: mapWorkspaceActions} = createNamespacedHelpers('workspace')

export default {
  name: "AddComment",
  props: {
    currentUser: Object,
    article_id: Number,
    timeline_id: Number,
    parent_id: Number,
  },
  data() {
    return {
      model: new AddCommentModel(),
    }
  },
  methods: {
    ...mapWorkspaceActions(['addComment']),
    async onAdd() {
      if (this.model.comment) {
        this.model.article_id = this.article_id;
        this.model.parent_id = this.parent_id;
        this.model.timeline_post_id = this.timeline_id;

        const {success} = await this.addComment(this.model);
        if (!success) {
          this.$toast(this.$t(`This comment not saved`), 'danger');
        }
        this.model = new AddCommentModel();
      }
    },
  },
}
</script>

<style scoped>

</style>