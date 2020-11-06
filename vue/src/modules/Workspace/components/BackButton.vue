<template>
  <b-button variant="light" class="mr-3" @click="$router.go(-1)">
    <i class="fas fa-arrow-left"></i>
    {{ $t('Back') }}
  </b-button>
</template>

<script>
export default {
  name: "BackButton"
}
</script>

<style scoped>

</style>