<template>
  <div id="workspace" class="page">
    <content-spinner :show="loading" :text="$t('Loading...')" :fullscreen="true" class="h-100"/>
    <div class="page-header">
      <b-breadcrumb :items="breadCrumb" class="d-none d-sm-flex"></b-breadcrumb>
      <b-button @click="showModal" variant="info">
        <i class="fas fa-plus-circle"></i>
        {{ $t('Add New Workspace') }}
      </b-button>
    </div>
    <div class="page-content p-3">
      <div class="workspace-list">
        <div class="row">
          <div v-for="(workspace) in workspaces" :key="`workspace-wrapper-${workspace.id}`"
               class="col-lg-2 col-sm-6 mb-3">
            <b-card no-body class="workspace-item overflow-hidden">
              <div class="image-wrapper d-flex align-items-center justify-content-center">
                <router-link :to="{name: 'workspace.view', params: {id: workspace.id}}">
                  <b-card-img :src="workspace.image_url || '/assets/logo.png'" class="rounded-0"/>
                </router-link>
              </div>
              <b-card-body>
                <b-card-title title-tag="h5">
                  <router-link :to="{name: 'workspace.view', params: {id: workspace.id}}">
                    {{ workspace.name }}
                  </router-link>
                </b-card-title>
              </b-card-body>
              <dropdown-buttons :item="workspace" @editClicked="onEditClicked" @removeClicked="onRemoveClicked"/>
            </b-card>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import {createNamespacedHelpers} from "vuex";
import ContentSpinner from "@/core/components/ContentSpinner";
import DropdownButtons from "@/core/components/DropdownButtons";

const {mapState: mapWorkspaceState, mapActions: mapWorkspaceActions} = createNamespacedHelpers('workspace');

export default {
  name: "Workspace",
  components: {DropdownButtons, ContentSpinner},
  data() {
    return {
      breadCrumb: [
        {
          text: this.$t('My Workspaces')
        }
      ]
    }
  },
  computed: {
    ...mapWorkspaceState(['loading', 'workspaces'])
  },
  methods: {
    ...mapWorkspaceActions(['showWorkspaceModal', 'getWorkspaces', 'deleteWorkspace']),
    showModal() {
      this.showWorkspaceModal(null)
    },
    onEditClicked(workspace) {
      this.showWorkspaceModal(workspace)
    },
    async onRemoveClicked(workspace) {
      const result = await this.$confirm(this.$t('All users and timeline records will be removed from this workspace. Are you sure you want to continue?'),
        this.$t('This operation can not be undone'))
      if (result) {
        const res = await this.deleteWorkspace(workspace);
        if (res.success) {
          this.$toast(this.$t(`The workspace '{name}' was successfully deleted`, {name: workspace.name}));
        } else {
          this.$toast(res.body.message, 'danger');
        }
      }
    },
  },
  created() {
    this.getWorkspaces();
  },
}
</script>

<style lang="scss" scoped>
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