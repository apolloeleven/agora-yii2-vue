<template>
  <b-button @click="$emit('onLikeClicked', item)" size="sm" pill :variant="liked ? 'info' : 'light'">
    <i class="far fa-thumbs-up fa-lg"/>
    {{ $t('Like') }}
    <b-badge v-if="item" class="ml-2" pill variant="secondary">
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
}
</script>

<style scoped>

</style>