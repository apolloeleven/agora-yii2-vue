<template>
  <div v-if="show" class="lobi-spinner d-flex flex-column justify-content-center align-items-center"
       :class="{'fullscreen-spinner': fullscreen}">
    <b-spinner></b-spinner>
    <div v-if="text" class="mt-3">{{text}}</div>
  </div>
</template>

<script>
  import i18n from "@/shared/i18n";

  export default {
    name: "ContentSpinner",
    props: {
      show: Boolean,
      fullscreen: {
        type: Boolean,
        default: false,
      },
      text: {
          type: String,
          default: i18n.t('Please wait...')
      }
    }
  }
</script>

<style lang="scss" scoped>
  .lobi-spinner {
    min-height: 100px;
  }
  .fullscreen-spinner{
    position: absolute;
    background-color: #FFF;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    z-index: 100;
  }
</style>