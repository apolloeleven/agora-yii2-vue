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
    <dropdown-button :model="workspace" type="workspace"/>
  </b-card>
</template>

<script>
import {createNamespacedHelpers} from "vuex";
import DropdownButton from "../components/DropdownButton";

const {mapActions} = createNamespacedHelpers('workspace');

export default {
  name: "WorkspaceItem",
  components: {DropdownButton},
  props: {
    index: Number,
    workspace: Object
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
</style>