<template>
  <div id="article-view" class="page">
    <div class="page-header">
      <back-button/>
      <b-breadcrumb :items="breadCrumb" class="d-none d-sm-flex"></b-breadcrumb>
      <b-button variant="info">
        <i class="fas fa-plus"></i>
        {{ buttonTitle }}
      </b-button>
      <!--<b-dropdown variant="link" no-caret right>
              <template v-slot:button-content>
                <b-button size="sm" pill variant="link">
                  <i class="fas fa-plus-circle fa-2x"></i>
                </b-button>
              </template>
              <b-dropdown-item>
                <i class="fas fa-plus"></i>
                {{ $t('Create new folder') }}
              </b-dropdown-item>
              <b-dropdown-item>
                <i class="fas fa-plus"></i>
                {{ $t('Create new article') }}
              </b-dropdown-item>
            </b-dropdown>-->
    </div>
  </div>
</template>

<script>
import BackButton from "../components/BackButton";
import {createNamespacedHelpers} from "vuex";

const {mapState, mapActions} = createNamespacedHelpers('article')

export default {
  name: "ArticleView",
  components: {BackButton},
  computed: {
    ...mapState(['breadCrumb', 'currentArticle']),
    buttonTitle() {
      if (this.currentArticle.depth === 0 && this.currentArticle.workspace.folder_in_folder) {
        return this.$t('Create new folder')
      }
      return this.$t('Create new article')
    },
  },
  methods: {
    ...mapActions(['getArticleBreadCrumb', 'getCurrentArticle']),
    async getBreadCrumb() {
      const res = await this.getArticleBreadCrumb(this.$route.params.id)
      if (!res.success) {
        this.$toast(this.$t(res.body), 'danger')
      }
    },
  },
  mounted() {
    this.getBreadCrumb();
    this.getCurrentArticle(this.$route.params.id);
  }
}
</script>

<style scoped>

</style>