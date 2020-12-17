<template>
  <b-button @click="$emit('onLikeClicked', item)" size="sm" pill :variant="liked ? 'info' : 'light'">
    <i class="far fa-thumbs-up fa-lg"/>
    {{ $t('Like') }}
    <b-badge v-if="item" class="ml-2" v-b-popover.hover.top.html="updateLikes()" pill variant="secondary">
      {{ item.length }}
    </b-badge>
  </b-button>
</template>

<script>
export default {
  name: "LikeUnlikeButton",
  props: {
    item: Array,
    liked: Boolean,
  },

  methods: {
    updateLikes() {
      this.likes = "";
      let limit = 10;
      let currentlimit = (this.item.length < limit) ? this.item.length : limit;
      for (let i = 0; i < currentlimit; i++) {
        this.likes += this.item[i].createdBy.displayName + "<br>"
      }
      if(this.item.length > limit) {
        this.likes += `And ${this.item.length - limit} other(s)`;
      }

      return {
        content: this.likes
      }
    },
  },
}
</script>

<style scoped>

</style>