<template>
  <ValidationObserver ref="form" v-slot="{ handleSubmit, invalid ,reset}">
    <b-modal
      :visible="show" id="article-form" ref="modal" size="lg" :title="modalTitle"
      @hidden="hideModal" @ok.prevent="handleSubmit(onSubmit)" :ok-title="$t('Submit')" scrollable>
      <b-form @submit.prevent="handleSubmit(onSubmit)" novalidate>
        <input-widget :model="model" attribute="title"></input-widget>
        <input-widget :model="model" attribute="body" type="richtext"></input-widget>
      </b-form>
    </b-modal>
  </ValidationObserver>
</template>

<script>
import InputWidget from "@/core/components/input-widget/InputWidget";
import ArticleFormModel from "./ArticleFormModel";
import {createNamespacedHelpers} from "vuex";

const {mapState, mapActions} = createNamespacedHelpers('workspace');

export default {
  name: "ArticleForm",
  components: {InputWidget},
  data() {
    return {
      model: new ArticleFormModel(),
    }
  },
  computed: {
    ...mapState({
      show: state => state.view.articles.modal.show,
      modalObject: state => state.view.articles.modal.object,
    }),
    modalTitle() {
      if (this.model.id) {
        return this.$t(`Update article '{name}'`, {name: this.model.title});
      }
      return this.$t(`Create new article`);
    },
  },
  watch: {
    modalObject() {
      if (this.modalObject) {
        this.model = new ArticleFormModel(this.modalObject);
      }
    },
  },
  methods: {
    ...mapActions(['hideArticleModal', 'createArticle', 'updateArticle']),
    async onSubmit() {
      let response;

      if (this.model.id) {
        response = await this.updateArticle(this.model);
      } else {
        response = await this.createArticle(this.model);
      }

      if (response.success) {
        const action = this.model.id ? 'updated' : 'created';
        this.$toast(this.$t(`The article '{title}' was successfully ${action}`, {title: this.model.title}));
        this.hideModal()
      } else {
        this.model.setMultipleErrors(response.body);
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