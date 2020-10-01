<template>
  <b-button v-if="tag === 'button'" size="sm" variant="outline-danger" @click="onDeleteClick">
    <i class="fas fa-trash-alt"></i>
    {{ $t('Delete') }}
  </b-button>
  <b-dropdown-item v-else @click="onDeleteClick">
    <i class="fas fa-trash-alt"></i>
    {{ $t('Delete') }}
  </b-dropdown-item>
</template>

<script>
import {createNamespacedHelpers} from "vuex";

const {mapActions} = createNamespacedHelpers('workspace');

export default {
  name: "DeleteButton",
  props: {
    model: Object,
    tag: String,
    type: String
  },
  methods: {
    ...mapActions(['deleteWorkspace']),
    async onDeleteClick() {
      if (this.type === 'workspace') {
        const result = await this.$confirm(this.$t('All users and timeline records will be removed from this workspace. Are you sure you want to continue?'),
          this.$t('This operation can not be undone'))
        if (result) {
          let workspaceName = this.model.name;
          const res = await this.deleteWorkspace(this.model);
          if (res.success) {
            this.$toast(this.$t(`The workspace '{name}' was successfully deleted`, {name: workspaceName}));
          } else {
            this.$toast(this.$t(`Unable to delete workspace`), 'danger');
          }
        }
      } else {
        //TODO for articles
      }
    },
  },
}
</script>

<style scoped>

</style>