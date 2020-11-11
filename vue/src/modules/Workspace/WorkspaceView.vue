<template>
  <div v-if="loading">
    <content-spinner show/>
  </div>
  <div v-else class="workspace-view page p-3">
    <div class="workspace-header">
      <div class="banner">
      </div>
      <div class="workspace-name-container">
        <h1 class="m-0">{{ workspace.name }}</h1>
      </div>
      <div class="workspace-img-container">
        <b-img :src="workspace.image_url || '/assets/logo.png'" fluid class="rounded-0"/>
      </div>
      <div class="menu-container">
        <b-nav tabs>
          <b-nav-item v-for="(item, index) in items" :to="item.to" active-class="active"
                      :key="`workspace-tab-${index}`">
            <i v-if="item.icon" :class="item.icon" class="mr-2"></i>{{ $t(item.title) }}
          </b-nav-item>
          <div class="ml-auto d-flex align-items-center pr-3">
            <slot name="right-placeholder"></slot>
          </div>
        </b-nav>
      </div>
    </div>
    <div class="page-content mt-3">
      <div class="content">
        <router-view/>
      </div>
      <div class="right-side-bar">
        <div class="card">
          <div class="card-header">
            {{ $t('Activities') }}
          </div>
          <div class="card-body">
            Lorem Ipsum Dolor
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import {createNamespacedHelpers} from "vuex";
import ContentSpinner from "@/core/components/ContentSpinner";

const {mapState, mapActions} = createNamespacedHelpers('workspace')

export default {
  name: "WorkspaceView",
  components: {ContentSpinner},
  data() {
    return {
      breadcrumbs: [],
      items: [
        {title: this.$i18n.t('Timeline'), to: {name: 'workspace.timeline'}, icon: 'fa fa-bars'},
        {title: this.$i18n.t('Files'), to: {name: 'workspace.files'}, icon: 'fa fa-folder'},
        {title: this.$i18n.t('Articles'), to: {name: 'workspace.articles'}, icon: 'fa fa-book'},
        {title: this.$i18n.t('About'), to: {name: 'workspace.about'}, icon: 'fa fa-info-circle'},
      ]
    }
  },
  computed: {
    ...mapState({
      workspace: state => state.view.workspace,
      loading: state => state.view.loading,
    }),
  },
  methods: {
    ...mapActions(['getCurrentWorkspace', 'destroyCurrentWorkspace']),
    initBreadcrumbs() {
      this.breadcrumbs = [
        {text: this.$i18n.t('My Workspaces'), to: {name: 'workspace'}},
        {text: this.workspace.abbreviation || this.workspace.name, active: true}
      ];
    },
  },
  async beforeMount() {
    await this.getCurrentWorkspace(this.$route.params.id);
    this.initBreadcrumbs();
  },
  destroyed() {
    this.destroyCurrentWorkspace({});
  }
}
</script>

<style lang="scss" scoped>
.workspace-view {
  .workspace-header {
    position: relative;

    .banner {
      background-color: #3989c6;
      background-image: linear-gradient(#c6e3fc, #3989c6);
      height: 7rem;
      z-index: 1;
    }

    .workspace-name-container {
      position: absolute;
      left: 10rem;
      top: 3.5rem;
      color: white;
    }

    .workspace-img-container {
      background-color: white;
      z-index: 2;
      border: 3px solid white;
      border-top-left-radius: 5px;
      border-top-right-radius: 5px;
      top: 1.25rem;
      left: 1rem;
      position: absolute;
      width: 8rem;
      height: 8rem;
    }

    .menu-container {
      padding-left: 9rem;
      background-color: white;

      .nav {
        border-bottom: none;

        .nav-item {
          .nav-link {
            padding: 0.85rem 1.25rem;
            color: #212529;

            &.active {
              background-color: transparent;
              border: 1px solid transparent;
              color: #3989c6;
            }

            &:hover {
              border: 1px solid transparent;
              color: #3989c6;
            }
          }
        }
      }
    }
  }

  .page-content {
    display: grid;
    grid-gap: 1em;
    grid-template-columns: repeat(6, 1fr);

    .content {
      grid-column: 1/6;
    }
  }
}
</style>