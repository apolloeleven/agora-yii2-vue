<template>
  <div class="article-item">
    <b-card no-body class="root-article-item" :style="'animation-delay: '+(index / 10)+'s'">
      <router-link :to="{name: 'article.view', params: {id: article.id}}">
        <b-media class="p-3 d-flex align-items-center">
          <template v-slot:aside>
            <b-img v-if="article.image_url" class="article-image" :src="article.image_url" rounded/>
            <i v-else class="fas fa-folder-open fa-4x"/>
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
.article-image {
  width: 64px;
  height: 64px;
  object-fit: cover;
}
</style>