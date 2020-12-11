<template>
  <div v-if="loading">
    <content-spinner show/>
  </div>
  <b-card v-else-if="article" class="article-item mb-3" no-body>
    <template v-slot:header>
      <h5 class="mb-0">{{ article.title }}</h5>
    </template>

    <b-card-body>
      <NoDataAvailable v-if="!article.body" :text="$t('This article does not have body')"/>
      <div v-html="article.body"/>
    </b-card-body>
  </b-card>
</template>

<script>
import {createNamespacedHelpers} from "vuex";
import NoDataAvailable from "../../../../core/components/NoDataAvailable";
import ContentSpinner from "../../../../core/components/ContentSpinner";

const {mapState: mapWorkspaceState, mapActions: mapWorkspaceActions} = createNamespacedHelpers('workspace');

export default {
  name: "ArticleView",
  components: {ContentSpinner, NoDataAvailable},
  computed: {
    ...mapWorkspaceState({
      article: state => state.view.articles.view.article,
      loading: state => state.view.articles.view.loading,
    }),
  },
  watch: {
    '$route.params.articleId': function (id) {
      this.getArticle(id)
    }
  },
  methods: {
    ...mapWorkspaceActions(['getArticle'])
  },
  mounted() {
    this.getArticle(this.$route.params.articleId)
  }
}
</script>

<style lang="scss" scoped>
.article-item {
  width: 680px;
  margin: 0 auto;
}
</style>