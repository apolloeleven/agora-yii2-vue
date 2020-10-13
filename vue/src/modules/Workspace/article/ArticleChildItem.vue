<template>
  <b-card no-body class="mb-3 animated fadeIn" :style="'animation-delay: '+(index / 5)+'s'" footer-tag="footer">
    <b-card-body>
      <b-media>
        <h5 class="mt-0">
          <router-link :to="{name: 'article.view', params: {id: model.id}}">
            {{ model.title }}
          </router-link>
        </h5>
        <p class="mb-0">
          <i class="far fa-clock"></i>
          {{ model.updated_at | relativeDate }}
        </p>
      </b-media>
      <b-media class="article-item mt-3">
        <div class="article-description mb-0" v-html="model.short_description"></div>
      </b-media>
    </b-card-body>
    <dropdown-button :model="model" :type="modalType"/>
  </b-card>
</template>

<script>
import {createNamespacedHelpers} from "vuex";
import DropdownButton from "../components/DropdownButton";

const {mapState, mapActions} = createNamespacedHelpers('article')

export default {
  name: "ArticleChildItem",
  components: {DropdownButton},
  props: {
    model: Object,
    index: Number
  },
  computed: {
    modalType() {
      return this.model.is_folder ? 'folder' : 'article';
    }
  }
}
</script>

<style lang="scss" scoped>
.article-item {
  .media-body {
    display: flex;
    align-items: start;
  }
}

.article-description {
  word-break: break-word;
}
</style>