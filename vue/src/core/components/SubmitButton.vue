<template>
  <b-button type="submit" :variant="variant" @click="click" :disabled="disabled || showLoader" :size="size">
    <span v-if="showLoader"
          class="spinner-border spinner-border-sm"
          role="status"
          style="width: 1.3em;height: 1.3em;margin-right: 1em"
          aria-hidden="true"></span>
    <slot v-if="hasDefaultSlot()"></slot>
    <template v-else>
      {{text}}
    </template>
  </b-button>
</template>

<script>
  import i18n from "../../shared/i18n";

  export default {
    name: "SubmitButton",
    props: {
      size: {
        type: String,
        default: 'md'
      },
      showLoader: {
        type: Boolean,
        default: false
      },
      disabled: {
        type: Boolean,
        default: false
      },
      text: {
        type: String,
        default: i18n.t('Submit')
      },
      variant: {
        type: String,
        default: 'primary'
      }
    },
    methods: {
      click($e) {
        this.$emit('click', $e)
      },
      hasDefaultSlot() {
        return !!this.$slots.default;
      }
    }
  }
</script>

<style scoped>

</style>
