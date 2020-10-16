<template>
  <b-card-body class="bg-light">
    <b-media>
      <template v-slot:aside>
        <b-img rounded="0" :src="'/assets/logo.png'" height="32"
               width="32"></b-img>
      </template>
      <b-input-group>
        <b-form-input v-model="model.comment" :placeholder="$t('Leave comment')"/>
        <b-input-group-append>
          <b-button @click="addComment" variant="info">{{ $t('Comment') }}</b-button>
        </b-input-group-append>
      </b-input-group>
    </b-media>
  </b-card-body>
</template>

<script>
import AddCommentModel from "./AddCommentModel";
import {createNamespacedHelpers} from "vuex";
import InputWidget from "../../../core/components/input-widget/InputWidget";

const {mapActions} = createNamespacedHelpers('article')

export default {
  name: "AddComment",
  components: {InputWidget},
  props: {
    article_id: Number,
    timeline_id: Number,
  },
  data() {
    return {
      model: new AddCommentModel(),
    }
  },
  methods: {
    ...mapActions(['saveComment']),
    async addComment() {
      if (this.model.comment) {
        this.model.article_id = this.article_id;
        this.model.timeline_post_id = this.timeline_id;

        const {success} = await this.saveComment(this.model);
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