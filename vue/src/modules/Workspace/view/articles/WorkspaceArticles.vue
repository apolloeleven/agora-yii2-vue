<template>
  <div v-if="loading">
    <content-spinner show/>
  </div>
  <div v-else class="articles-wrapper">
    <div class="row">
      <div class="col-md-12 text-right pb-3">
        <b-button @click="onCreateArticleClick" size="sm" variant="outline-primary">
          <i class="fas fa-plus-circle"/>
          {{ $t('Create Article') }}
        </b-button>
      </div>
    </div>
    <div class="article-item" v-for="(article) in articles" :key="`article-item-${article.id}`">
      <b-card no-body class="mb-3">
        <div class="p-3">
          <router-link class="d-inline-block" :to="{name: 'article.view', params: {id: article.id}}">
            <h5 class="mb-0">{{ article.title }}</h5>
          </router-link>
          <div v-if="article.short_description && article.short_description.length > 0"
               v-html="article.short_description" class="mt-3"></div>
          <small class="mt-3 text-muted d-block">
            <i class="far fa-clock"></i>
            {{ article.updated_at | relativeDate }}
          </small>
        </div>
        <dropdown-buttons :item="article" @editClicked="onEditClicked" @removeClicked="onRemoveClicked"/>
      </b-card>
    </div>
  </div>
</template>

<script>

import {createNamespacedHelpers} from "vuex";
import DropdownButtons from "@/core/components/DropdownButtons";
import ContentSpinner from "@/core/components/ContentSpinner";

const {mapState, mapActions} = createNamespacedHelpers('workspace');

export default {
  name: "WorkspaceArticles",
  components: {ContentSpinner, DropdownButtons},
  computed: {
    ...mapState({
      workspace: state => state.view.workspace,
      articles: state => state.view.articles.data,
      loading: state => state.view.articles.loading,
    })
  },
  methods: {
    ...mapActions(['getArticles', 'showArticleModal', 'deleteArticle']),
    onCreateArticleClick() {
      this.showArticleModal({object: {workspace_id: this.workspace.id}});
    },
    onEditClicked(article) {
      this.showArticleModal({object: article});
    },
    async onRemoveClicked(article) {
      let success = await this.$confirm(this.$i18n.t("Are you sure you want to remove following article?"));
      if (success) {
        let response = await this.deleteArticle(article.id);
        if (response.success) {
          this.$toast(this.$i18n.t("Article was successfully deleted"));
        }
      }
    },
  },
  beforeMount() {
    this.getArticles(this.workspace.id);
  }
}
</script>

<style scoped>

</style>