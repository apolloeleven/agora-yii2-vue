<template>
  <b-card no-body class="workspace-item overflow-hidden">
    <div class="image-wrapper d-flex align-items-center justify-content-center">
      <router-link :to="{name: 'workspace.view', params: {id: workspace.id}}">
        <b-card-img
            :src="workspace.image_url || '/assets/logo.png'"
            class="rounded-0"/>
      </router-link>
    </div>
    <b-card-body>
      <b-card-title title-tag="h5">
        <router-link :to="{name: 'workspace.view', params: {id: workspace.id}}">{{ workspace.name }}</router-link>
      </b-card-title>
    </b-card-body>
    <b-dropdown class="actions-button" variant="link" no-caret right>
      <template v-slot:button-content>
        <b-button size="sm" pill variant="light">
          <i class="fas fa-ellipsis-v"></i>
        </b-button>
      </template>
      <b-dropdown-item @click="onEditClick">
        <i class="fas fa-pencil-alt"></i>
        {{ $t('Edit') }}
      </b-dropdown-item>
      <b-dropdown-item @click="onDeleteClick">
        <i class="fas fa-trash-alt"></i>
        {{ $t('Delete') }}
      </b-dropdown-item>
    </b-dropdown>
  </b-card>
</template>

<script>
import {createNamespacedHelpers} from "vuex";

const {mapActions} = createNamespacedHelpers('workspace');

export default {
  name: "WorkspaceItem",
  props: {
    index: Number,
    workspace: Object
  },
  methods: {
    ...mapActions(['showWorkspaceModal', 'deleteWorkspace']),
    onEditClick() {
      this.showWorkspaceModal(this.workspace)
    },
    async onDeleteClick() {
      const result = await this.$confirm(this.$t('All users and timeline records will be removed from this workspace. Are you sure you want to continue?'),
          this.$t('This operation can not be undone'))
      if (result) {
        let workspaceName = this.workspace.name;
        const res = await this.deleteWorkspace(this.workspace);
        if (res.success) {
          this.$toast(this.$t(`The workspace '{name}' was successfully deleted`, {name: workspaceName}));
        } else {
          this.$toast(this.$t(`Unable to delete workspace`), 'danger');
        }
      }
    },
  },
}
</script>

<style lang="scss" scoped>
@import "../../../core/scss/variables";

.workspace-item {
  height: 100%;
  transition: all 0.3s;

  &:hover {
    box-shadow: 2px 2px 6px rgba(0, 0, 0, 0.1);
  }

  .image-wrapper {
    height: 140px;
  }

  .description {
    & /deep/ p {
      &:last-of-type {
        margin-bottom: 0;
      }
    }
  }
}

.actions-button {
  position: absolute;
  right: 0;
  top: 0;
}
</style>