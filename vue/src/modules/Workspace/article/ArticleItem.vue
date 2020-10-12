<template>
  <div class="article-item">
    <b-card no-body class="root-article-item" :style="'animation-delay: '+(index / 10)+'s'">
      <router-link :to="{name: 'article.view', params: {id: article.id}}">
        <b-media class="p-3 d-flex align-items-center">
          <template v-slot:aside>
            <i class="fas fa-folder-open fa-4x"></i>
          </template>
          <h5 class="m-0">{{ article.title }}</h5>
          <div v-html="article.short_description"></div>
        </b-media>
      </router-link>
      <dropdown-button :model="article" type="folder"/>
    </b-card>
  </div>
</template>

<script>
import DropdownButton from "../components/DropdownButton";

export default {
  name: "ArticleItem",
  components: {DropdownButton},
  props: {
    article: Object,
    index: Number
  },
}
</script>

<style lang="scss" scoped>
</style>