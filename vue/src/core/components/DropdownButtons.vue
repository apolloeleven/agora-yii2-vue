<template>
  <b-dropdown class="actions-button" variant="link" no-caret right>
    <template v-slot:button-content>
      <b-button size="sm" pill variant="light">
        <i class="fas fa-ellipsis-v fa-lg"/>
      </b-button>
    </template>
    <b-dropdown-item @click="$emit('editClicked', item)">
      <i class="fas fa-pencil-alt mr-2"></i>
      {{ $t('Edit') }}
    </b-dropdown-item>
    <b-dropdown-item @click="$emit('removeClicked', item)">
      <i class="far fa-trash-alt mr-2"></i>
      {{ $t('Remove') }}
    </b-dropdown-item>
  </b-dropdown>
</template>

<script>

export default {
  name: "DropdownButtons",
  props: {
    item: Object,
  },
}

</script>

<style scoped>
.actions-button {
  position: absolute;
  right: 0;
  top: 0;
}
</style>