<template>
  <div v-if="loading">
    <content-spinner show/>
  </div>
  <div v-else class="article-view">
    <div class="card">
      <div class="card-header">
        <h5 class="mb-0">{{ model.id ? $t('Update Article') : $t('Create Article') }}</h5>
      </div>
      <div class="card-body">
        <ValidationObserver ref="form" v-slot="{ handleSubmit, invalid ,reset}">
          <b-form @submit.prevent="handleSubmit(onSubmit)" novalidate>
            <input-widget :model="model" attribute="title" :autofocus="true"></input-widget>
            <input-widget :model="model" attribute="body" type="richtext"></input-widget>
            <submit-button :disabled="submitLoading" :show-loader="submitLoading"/>
          </b-form>
        </ValidationObserver>
      </div>
    </div>
  </div>
</template>

<script>
import {createNamespacedHelpers} from "vuex";
import ContentSpinner from "@/core/components/ContentSpinner";
import ArticleFormModel from "@/modules/Workspace/view/articles/ArticleFormModel";
import InputWidget from "@/core/components/input-widget/InputWidget";
import httpService from "@/core/services/httpService";
import SubmitButton from "@/core/components/SubmitButton";

const {mapState, mapActions} = createNamespacedHelpers('workspace')

export default {
  name: "ArticleForm",
  components: {SubmitButton, InputWidget, ContentSpinner},
  data() {
    return {
      model: new ArticleFormModel(),
      submitLoading: false
    }
  },
  computed: {
    ...mapState({
      workspace: state => state.view.workspace,
      article: state => state.view.articles.view.article,
      loading: state => state.view.articles.view.loading,
    })
  },
  watch: {
    article() {
      this.model = new ArticleFormModel(this.article);
    }
  },
  methods: {
    ...mapActions(['getArticle']),
    async onSubmit() {
      let response;

      this.submitLoading = true;
      if (this.model.id) {
        response = await httpService.put(`/v1/workspaces/article/${this.model.id}`, this.model);
      } else {
        this.model.workspace_id = this.workspace.id;
        response = await httpService.post('/v1/workspaces/article', this.model);
      }
      this.submitLoading = false;

      if (response.success) {
        this.$router.push({
          name: 'workspace.articles',
          params: {id: this.workspace.id}
        });
      } else {
        this.model.setMultipleErrors(response.body);
      }
    }
  },
  beforeMount() {
    if (this.$route.params.articleId) {
      this.getArticle(this.$route.params.articleId)
    }
  }
}
</script>

<style scoped>

</style>