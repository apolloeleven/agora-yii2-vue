<template>
  <div class="page-footer bg-white d-flex justify-content-end align-items-center border-top p-3">
    <slot name="default"></slot>
  </div>
</template>
<script>
export default {
  name: "PageFooter",
}
</script>
<style lang="scss" scoped>
.page-footer {
  z-index: 2;
  box-shadow: 0 -0.125rem 0.25rem rgba(0, 0, 0, 0.075);
}
</style>
