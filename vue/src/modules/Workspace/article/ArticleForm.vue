<template>
  <ValidationObserver ref="form" v-slot="{ handleSubmit, invalid ,reset}">
    <b-modal
      :visible="showModal" id="article-form" ref="modal" size="lg" :title="modalTitle"
      @hidden="hideArticleModal" @ok.prevent="handleSubmit(onSubmit)" :ok-title="$t('Submit')" scrollable>
      <b-form @submit.prevent="handleSubmit(onSubmit)" novalidate>
        <b-form-group :label="$t('Upload Image')">
          <input :model="model.image" type="file"/>
        </b-form-group>
        <input-widget :model="model" attribute="title"></input-widget>
        <input-widget :model="model" attribute="body" type="richtext"></input-widget>
      </b-form>
    </b-modal>
  </ValidationObserver>
</template>

<script>
import InputWidget from "../../../core/components/input-widget/InputWidget";
import ArticleFormModel from "./ArticleFormModel";
import {createNamespacedHelpers} from "vuex";

const {mapState, mapActions} = createNamespacedHelpers('article');

export default {
  name: "ArticleForm",
  components: {InputWidget},
  data() {
    return {
      model: new ArticleFormModel(),
    }
  },
  computed: {
    ...mapState(['showModal', 'modalArticle']),
    modalTitle() {
      if (this.model.article_id) {
        if (this.modalArticle) {
          return this.$t(`Update article '{name}'`, {name: this.modalArticle.title});
        } else {
          return this.$t(`Create new article`);
        }
      } else {
        if (this.modalArticle) {
          return this.$t(`Update folder '{name}'`, {name: this.modalArticle.title});
        } else {
          return this.$t(`Create new folder`);
        }
      }
    }
  },
  watch: {
    modalArticle() {
      const ar = this.modalArticle;
      if (ar) {
        this.model.id = ar.id;
        this.model.workspace_id = ar.workspace_id;
        this.model.article_id = ar.article_id;
        this.model.title = ar.title;
        this.model.body = ar.body || '';
      } else {
        this.model = new ArticleFormModel();
      }
    },
  },
  methods: {
    ...mapActions(['hideArticleModal', 'createArticle']),
    async onSubmit() {
      this.resource = 'folder';
      this.model.workspace_id = this.$route.params.id;
      let res = await this.createArticle(this.model);
      if (res.success) {
        this.$toast(this.$t(`The ${this.resource} '{title}' was successfully ${this.action}`, {title: this.model.title}));
      } else {
        this.$toast(this.$t(`The ${this.resource} '{title}' was not ${this.action}`, {title: this.model.title}));
      }
      this.hideArticleModal()
      this.model = new ArticleFormModel()
    }
  }
}
</script>

<style scoped>

</style>