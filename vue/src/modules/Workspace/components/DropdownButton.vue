<template>
  <b-dropdown class="actions-button" variant="link" no-caret right>
    <template v-slot:button-content>
      <b-button size="sm" pill variant="light">
        <i class="fas fa-ellipsis-v fa-lg"/>
      </b-button>
    </template>
    <edit-button :model="model" :type="type"/>
    <delete-button :model="model" :type="type"/>
  </b-dropdown>
</template>

<script>
import EditButton from "./EditButton";
import DeleteButton from "./DeleteButton";

export default {
  name: "DropdownButton",
  props: {
    model: Object,
    type: String,
  },
  components: {DeleteButton, EditButton}
}
</script>

<style scoped>
.actions-button {
  position: absolute;
  right: 0;
  top: 0;
}
</style>