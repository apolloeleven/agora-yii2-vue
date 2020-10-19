<template>
  <b-button @click="onLike" size="sm" pill :variant="liked ? 'info' : 'light'">
    <i class="far fa-thumbs-up fa-lg"/>
    {{ $t('Like') }}
    <b-badge v-if="model.userLikes" class="ml-2" pill variant="secondary">
      {{ model.userLikes.length }}
    </b-badge>
  </b-button>
</template>

<script>
import {createNamespacedHelpers} from "vuex";

const {mapActions} = createNamespacedHelpers('article');

export default {
  name: "LikeUnlikeButton",
  props: {
    model: Object,
    type: String,
  },
  computed: {
    liked() {
      return !!(this.model.myLikes && this.model.myLikes.length > 0);
    }
  },
  methods: {
    ...mapActions(['like', 'unlike']),
    async onLike() {
      const params = {}
      if (this.type === 'timeline') {
        params.timeline_post_id = this.model.id;
      } else {
        params.article_id = this.model.id;
      }
      if (this.liked) {
        params.id = this.model.myLikes[0].id;
        await this.unlike(params);
      } else {
        await this.like(params);
      }
    },
  },
}
</script>

<style scoped>

</style>