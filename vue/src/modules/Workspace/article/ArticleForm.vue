<template>
  <ValidationObserver ref="form" v-slot="{ handleSubmit, invalid ,reset}">
    <b-modal
      :visible="showModal" id="article-form" ref="modal" size="lg" :title="modalTitle"
      @hidden="hideModal" @ok.prevent="handleSubmit(onSubmit)" :ok-title="$t('Submit')" scrollable>
      <b-form @submit.prevent="handleSubmit(onSubmit)" novalidate>
        <input-widget
          :model="model" attribute="image" type="file" :placeholder="$t('Choose a image or drop it here...')">
        </input-widget>
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
    }
  },
  computed: {
    ...mapState(['showModal', 'modalArticle', 'currentArticle', 'isArticle']),
    ...mapWorkspaceState(['currentWorkspace']),
    modalTitle() {
      if (this.isArticle) {
        if (this.modalArticle) {
          return this.$t(`Update article '{name}'`, {name: this.modalArticle.title});
        }
        return this.$t(`Create new article`);
      } else {
        if (this.modalArticle) {
          return this.$t(`Update folder '{name}'`, {name: this.modalArticle.title});
        }
        return this.$t(`Create new folder`);
      }
    }
  },
  watch: {
    modalArticle() {
      if (this.modalArticle) {
        this.model = new ArticleFormModel(this.modalArticle);
      }
    },
  },
  methods: {
    ...mapActions(['hideArticleModal', 'createArticle', 'updateArticle', 'getArticlesByParent', 'getArticlesByWorkspace']),
    async onSubmit() {
      let action;
      let resource

      resource = 'folder';
      if (this.currentWorkspace.id) {
        this.model.workspace_id = this.currentWorkspace.id;
      }
      this.model.article_id = this.currentArticle.id;
      this.model.isArticle = this.isArticle;

      if (this.isArticle) {
        resource = 'article';
      } else {
        resource = 'folder';
      }
      let res
      if (this.model.id) {
        action = 'updated';
        res = await this.updateArticle({...this.model.toJSON()});
      } else {
        action = 'created';
        res = await this.createArticle({...this.model.toJSON()});
      }

      if (this.model.article_id) {
        this.getArticlesByParent(this.model.article_id);
      } else if (this.model.workspace_id) {
        this.getArticlesByWorkspace(this.model.workspace_id);
      }

      if (res.success) {
        this.$toast(this.$t(`The ${resource} '{title}' was successfully ${action}`, {title: this.model.title}));
        this.hideModal()
      } else {
        this.$toast(this.$t(`The ${resource} '{title}' was not ${action}`, {title: this.model.title}), 'danger');
      }
    },
    hideModal() {
      this.hideArticleModal();
      this.model = new ArticleFormModel()
    },
  },
}
</script>

<style scoped>

</style>