<template>
  <div class="page-header bg-white shadow-sm d-flex justify-content-between align-items-center p-3">
    <div class="title">
      <h1 class="mb-0" v-if="title">{{title}}</h1>
      <slot v-else name="title"></slot>
    </div>
    <div class="buttons text-right">
      <slot name="default"></slot>
    </div>
  </div>
</template>
<script>
  export default {
    name: "PageHeader",
    props: {
      title: {
        type: String,
        required: false
      }
    }
  }
</script>
<style lang="scss" scoped>
  @import "./../scss/index";

  .page-header {
    //padding: 1em;
    //border-radius: 0.4em;
    //margin-bottom: 1rem;

    .title {
      display: inline-flex;
      align-items: center;
      flex: 1;

      h1 {
        font-size: 1.5em;
      }
    }
  }

  @media only screen and (max-width: map-get($grid-breakpoints, "lg")) {
    .page-header {
      flex-direction: column;
      align-items: start !important;

      .title {
        margin-bottom: 1em;
      }
    }
  }

</style>
