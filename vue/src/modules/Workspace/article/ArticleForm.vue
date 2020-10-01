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
const {mapState: mapWorkspaceState} = createNamespacedHelpers('workspace');

export default {
  name: "ArticleForm",
  components: {InputWidget},
  data() {
    return {
      model: new ArticleFormModel(),
      action: '',
      resource: '',
    }
  },
  computed: {
    ...mapState(['showModal', 'modalArticle']),
    ...mapWorkspaceState(['currentWorkspace']),
    modalTitle() {
      if (this.model.workspace_id) {
        if (this.modalArticle) {
          return this.$t(`Update folder '{name}'`, {name: this.modalArticle.title});
        } else {
          return this.$t(`Create new folder`);
        }
      } else {
        if (this.modalArticle) {
          return this.$t(`Update article '{name}'`, {name: this.modalArticle.title});
        } else {
          return this.$t(`Create new article`);
        }
      }
    }
  },
  watch: {
    modalArticle() {
      const a = this.modalArticle;
      if (a) {
        this.model.id = a.id;
        this.model.workspace_id = a.workspace_id;
        this.model.article_id = a.article_id;
        this.model.title = a.title;
        this.model.body = a.body || '';
      } else {
        this.model = new ArticleFormModel();
      }
    },
  },
  methods: {
    ...mapActions(['hideArticleModal', 'createArticle', 'updateArticle']),
    async onSubmit() {
      this.resource = 'folder';
      this.model.workspace_id = this.currentWorkspace.id;

      if (this.model.workspace_id) {
        this.resource = 'folder';
      } else {
        this.resource = 'article';
      }

      let res
      if (this.model.id) {
        this.action = 'updated';
        res = await this.updateArticle(this.model);
      } else {
        this.action = 'created';
        res = await this.createArticle(this.model);
      }

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