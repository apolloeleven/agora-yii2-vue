<template>
  <div id="article-view" class="page">
    <div class="page-header">
      <back-button/>
      <b-breadcrumb :items="breadCrumb" class="d-none d-sm-flex"></b-breadcrumb>
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
    ...mapState(['breadCrumb'])
  },
  methods: {
    ...mapActions(['getArticleBreadCrumb']),
    async getBreadCrumb() {
      const res = await this.getArticleBreadCrumb(this.$route.params.id)
      if (!res.success) {
        this.$toast(this.$t(res.body), 'danger')
      }
    },
  },
  mounted() {
    this.getBreadCrumb();
  }
}
</script>

<style scoped>

</style>