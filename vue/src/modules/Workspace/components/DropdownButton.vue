<template>
  <b-dropdown v-if="permission" class="actions-button" variant="link" no-caret right>
    <template v-slot:button-content>
      <b-button size="sm" pill variant="light">
        <i class="fas fa-ellipsis-v fa-lg"/>
      </b-button>
    </template>
    <edit-button v-if="permissionForEdit" :model="model" :type="type"/>
    <delete-button v-if="permissionForDelete" :model="model" :type="type"/>
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
    permission: {
      type: Boolean,
      default: true,
    },
    permissionForEdit: {
      type: Boolean,
      default: true,
    },
    permissionForDelete: {
      type: Boolean,
      default: true,
    }
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