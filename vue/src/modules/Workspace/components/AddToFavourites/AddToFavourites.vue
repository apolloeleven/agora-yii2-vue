<template>
  <b-button pill @click="addToFavourites" variant="link" class="ml-3 text-warning" v-b-tooltip.hover style="padding: 0"
            :title="inFavourites ? $t('Remove from favourites') : $t('Add to favourites')">
    <i v-if="!inFavourites" class="far fa-star fa-2x"></i>
    <i v-else class="fas fa-star fa-2x"></i>
  </b-button>
</template>

<script>
import FavouritesService from "./FavouritesService";

export default {
  name: "AddToFavourites",
  props: {
    name: String,
    isFolder: Boolean,
  },
  computed: {
    inFavourites() {
      return FavouritesService.inFavourites(this.$route.path);
    }
  },
  methods: {
    addToFavourites() {
      if (FavouritesService.inFavourites(this.$route.path)) {
        FavouritesService.removeFavourite(this.$route.path)
      } else {
        let icon
        if (this.isFolder) {
          icon = 'fas fa-folder-open'
        } else {
          icon = 'fas fa-link'
        }
        FavouritesService.addFavourite(this.name, this.$route.path, icon)
      }
    }
  }
}
</script>

<style scoped>

</style>