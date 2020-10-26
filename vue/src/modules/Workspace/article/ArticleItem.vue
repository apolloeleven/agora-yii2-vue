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
      <b-dropdown class="actions-button" variant="link" no-caret right>
        <template v-slot:button-content>
          <b-button size="sm" pill variant="light">
            <i class="fas fa-ellipsis-v"></i>
          </b-button>
        </template>
        <edit-button :model="article" type="folder"/>
        <delete-button :model="article" type="folder"/>
      </b-dropdown>
    </b-card>
  </div>
</template>

<script>
import EditButton from "../components/EditButton";
import DeleteButton from "../components/DeleteButton";

export default {
  name: "ArticleItem",
  components: {DeleteButton, EditButton},
  props: {
    article: Object,
    index: Number
  },
}
</script>

<style lang="scss" scoped>
.actions-button {
  position: absolute;
  right: 0;
  top: 0;
}

.article-image {
  width: 64px;
  height: 64px;
  object-fit: cover;
}
</style>