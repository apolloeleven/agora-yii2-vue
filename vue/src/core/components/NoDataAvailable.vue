<template>
  <div class="no-data-available text-center text-muted d-flex flex-column justify-content-center align-items-center"
       :style="{'min-height': `${height}px`}">
    {{text || $t('No Data Available')}}
  </div>
</template>

<script>
  export default {
    name: 'NoDataAvailable',
    props: {
      height: {
        type: [Number, String],
        default: 1
      },
      text: {
        type: String,
        default: ''
      }
    }
  };
</script>

<style lang="scss">
</style>
