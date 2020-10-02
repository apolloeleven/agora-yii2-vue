<template>
  <b-card no-body class="mb-3 animated fadeIn" :style="'animation-delay: '+(index / 5)+'s'" footer-tag="footer">
    <b-card-body>
      <b-media>
        <h5 class="mt-0">
          <router-link :to="{name: 'article.view', params: {id: article.id}}">
            {{ article.title }}
          </router-link>
        </h5>
        <p class="mb-0">
          <i class="fas fa-clock"></i>
          {{ article.updated_at | relativeDate }}
        </p>
      </b-media>
      <b-media class="article-item mt-3">
        <div class="article-description mb-0" v-html="article.short_description"></div>
      </b-media>
    </b-card-body>

    <b-dropdown class="actions-button" variant="link" no-caret right>
      <template v-slot:button-content>
        <b-button size="sm" pill variant="light">
          <i class="fas fa-ellipsis-v fa-lg"></i>
        </b-button>
      </template>
      <edit-button :model="article" :type="modalType"/>
      <delete-button :model="article" :type="modalType"/>
    </b-dropdown>
  </b-card>
</template>

<script>
import {createNamespacedHelpers} from "vuex";
import EditButton from "../components/EditButton";
import DeleteButton from "../components/DeleteButton";

const {mapState, mapActions} = createNamespacedHelpers('article')

export default {
  name: "ArticleChildItem",
  components: {DeleteButton, EditButton},
  props: {
    index: Number,
    article: Object
  },
  computed: {
    modalType() {
      return this.article.is_folder ? 'folder' : 'article';
    }
  }
}
</script>

<style lang="scss" scoped>
.actions-button {
  position: absolute;
  right: 0;
  top: 0;
}

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