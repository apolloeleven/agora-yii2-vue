<template>
  <b-button class="upload-file" block @mousedown="$refs.filesInput.value = ''">
    <input ref="filesInput" type="file" @change="onFileChoose">
    <i class="fas fa-photo-video"></i>
    {{ $t('Photo/Video') }}
  </b-button>
</template>

<script>
export default {
  name: "PhotoVideoInput",
  methods: {
    onFileChoose(ev) {
      this.$emit('change', ev);
    }
  }
}
</script>

<style lang="scss" scoped>

.upload-file {
  position: relative;
  input {
    opacity: 0;
    position: absolute;
    left: 0;
    right: 0;
    top: 0;
    bottom: 0;
    width: 100%;
    height: 100%;
  }
}
</style>