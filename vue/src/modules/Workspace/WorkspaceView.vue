<template>
  <div v-if="loading">
    <content-spinner show/>
  </div>
  <div v-else class="workspace-view page">
    <div @scroll="onScroll" ref="workspaceContent" class="workspace-content">
      <div ref="workspaceHeader" class="workspace-header">
        <div ref="banner" class="banner">
        </div>
        <div class="workspace-name-container">
          <h1 class="m-0">{{ workspace.name }}</h1>
        </div>
        <div class="workspace-img-container">
          <b-img :src="workspace.image_url || '/assets/logo.png'" fluid class="rounded-0"/>
        </div>
        <div class="menu-container" :class="{'header-fixed': headerFixed}">
          <b-nav ref="headerNav" tabs>
            <b-nav-item class="workspace-img-container">
              <b-img :src="workspace.image_url || '/assets/logo.png'" fluid class="rounded-0"/>
            </b-nav-item>
            <b-nav-item :active="isActive(item)" v-for="(item, index) in items" :to="item.to" active-class="active"
                        :key="`workspace-tab-${index}`">
              <i v-if="item.icon" :class="item.icon" class="mr-2"></i>{{ $t(item.title) }}
            </b-nav-item>
            <div class="ml-auto d-flex align-items-center pr-3">
              <slot name="right-placeholder"></slot>
              <b-button @click="onInviteClick" variant="info">
                <i class="fas fa-plus-circle"/>
                {{ $t('Invite') }}
              </b-button>
            </div>
          </b-nav>
        </div>
      </div>
      <div class="content p-3">
        <router-view/>
      </div>
    </div>

    <div class="workspace-sidebar">
      <div class="right-side-bar">
        <b-card :title="$t('Contacts')" no-body>
          <b-card-body>
            <h3 class="card-title mb-0">{{ $t('Contacts') }}</h3>
          </b-card-body>
          <b-list-group style="max-width: 300px;">
            <b-list-group-item class="d-flex align-items-center">
              <b-avatar class="mr-3"></b-avatar>
              <span class="mr-auto">J. Circlehead</span>
              <b-badge>5</b-badge>
            </b-list-group-item>
            <b-list-group-item class="d-flex align-items-center">
              <b-avatar variant="primary" text="BV" class="mr-3"></b-avatar>
              <span class="mr-auto">BootstrapVue</span>
              <b-badge>12</b-badge>
            </b-list-group-item>
            <b-list-group-item class="d-flex align-items-center">
              <b-avatar variant="info" src="https://placekitten.com/300/300" class="mr-3"></b-avatar>
              <span class="mr-auto">Super Kitty</span>
              <b-badge>9</b-badge>
            </b-list-group-item>
            <b-list-group-item class="d-flex align-items-center">
              <b-avatar variant="success" icon="people-fill" class="mr-3"></b-avatar>
              <span class="mr-auto">ACME group</span>
              <b-badge>7</b-badge>
            </b-list-group-item>
            <b-list-group-item class="d-flex align-items-center">
              <b-avatar variant="success" icon="people-fill" class="mr-3"></b-avatar>
              <span class="mr-auto">ACME group</span>
              <b-badge>7</b-badge>
            </b-list-group-item>
            <b-list-group-item class="d-flex align-items-center">
              <b-avatar variant="success" icon="people-fill" class="mr-3"></b-avatar>
              <span class="mr-auto">ACME group</span>
              <b-badge>7</b-badge>
            </b-list-group-item>
            <b-list-group-item class="d-flex align-items-center">
              <b-avatar variant="success" icon="people-fill" class="mr-3"></b-avatar>
              <span class="mr-auto">ACME group</span>
              <b-badge>7</b-badge>
            </b-list-group-item>
            <b-list-group-item class="d-flex align-items-center">
              <b-avatar variant="success" icon="people-fill" class="mr-3"></b-avatar>
              <span class="mr-auto">ACME group</span>
              <b-badge>7</b-badge>
            </b-list-group-item>
            <b-list-group-item class="d-flex align-items-center">
              <b-avatar variant="success" icon="people-fill" class="mr-3"></b-avatar>
              <span class="mr-auto">ACME group</span>
              <b-badge>7</b-badge>
            </b-list-group-item>
            <b-list-group-item class="d-flex align-items-center">
              <b-avatar variant="success" icon="people-fill" class="mr-3"></b-avatar>
              <span class="mr-auto">ACME group</span>
              <b-badge>7</b-badge>
            </b-list-group-item>
            <b-list-group-item class="d-flex align-items-center">
              <b-avatar variant="success" icon="people-fill" class="mr-3"></b-avatar>
              <span class="mr-auto">ACME group</span>
              <b-badge>7</b-badge>
            </b-list-group-item>
            <b-list-group-item class="d-flex align-items-center">
              <b-avatar variant="success" icon="people-fill" class="mr-3"></b-avatar>
              <span class="mr-auto">ACME group</span>
              <b-badge>7</b-badge>
            </b-list-group-item>
            <b-list-group-item class="d-flex align-items-center">
              <b-avatar variant="success" icon="people-fill" class="mr-3"></b-avatar>
              <span class="mr-auto">ACME group</span>
              <b-badge>7</b-badge>
            </b-list-group-item>
            <b-list-group-item class="d-flex align-items-center">
              <b-avatar variant="success" icon="people-fill" class="mr-3"></b-avatar>
              <span class="mr-auto">ACME group</span>
              <b-badge>7</b-badge>
            </b-list-group-item>
            <b-list-group-item class="d-flex align-items-center">
              <b-avatar variant="success" icon="people-fill" class="mr-3"></b-avatar>
              <span class="mr-auto">ACME group</span>
              <b-badge>7</b-badge>
            </b-list-group-item>
            <b-list-group-item class="d-flex align-items-center">
              <b-avatar variant="success" icon="people-fill" class="mr-3"></b-avatar>
              <span class="mr-auto">ACME group</span>
              <b-badge>7</b-badge>
            </b-list-group-item>
            <b-list-group-item class="d-flex align-items-center">
              <b-avatar variant="success" icon="people-fill" class="mr-3"></b-avatar>
              <span class="mr-auto">ACME group</span>
              <b-badge>7</b-badge>
            </b-list-group-item>
            <b-list-group-item class="d-flex align-items-center">
              <b-avatar variant="success" icon="people-fill" class="mr-3"></b-avatar>
              <span class="mr-auto">ACME group</span>
              <b-badge>7</b-badge>
            </b-list-group-item>
            <b-list-group-item class="d-flex align-items-center">
              <b-avatar variant="success" icon="people-fill" class="mr-3"></b-avatar>
              <span class="mr-auto">ACME group</span>
              <b-badge>7</b-badge>
            </b-list-group-item>
          </b-list-group>
        </b-card>
      </div>
    </div>
    <WorkspaceInviteModal/>
  </div>
</template>

<script>
import {createNamespacedHelpers} from "vuex";
import ContentSpinner from "@/core/components/ContentSpinner";
import WorkspaceInviteModal from "./view/invite/WorkspaceInviteModal";

const {mapState, mapActions} = createNamespacedHelpers('workspace')

export default {
  name: "WorkspaceView",
  components: {WorkspaceInviteModal, ContentSpinner},
  data() {
    return {
      headerFixed: false,
      breadcrumbs: [],
    }
  },
  computed: {
    ...mapState({
      workspace: state => state.view.workspace,
      loading: state => state.view.loading,
    }),
    items() {
      return [
        {title: this.$i18n.t('Timeline'), to: {name: 'workspace.timeline'}, icon: 'fa fa-bars'},
        {
          title: this.$i18n.t('Files'),
          to: {name: 'workspace.files', params: {folderId: this.workspace.rootFolder.id}},
          icon: 'fa fa-folder'
        },
        {title: this.$i18n.t('Articles'), to: {name: 'workspace.articles'}, icon: 'fa fa-book'},
        {title: this.$i18n.t('About'), to: {name: 'workspace.about'}, icon: 'fa fa-info-circle'},
      ]
    },
  },
  methods: {
    ...mapActions(['getCurrentWorkspace', 'destroyCurrentWorkspace', 'showInviteModal']),
    initBreadcrumbs() {
      this.breadcrumbs = [
        {text: this.$i18n.t('My Workspaces'), to: {name: 'workspace'}},
        {text: this.workspace.abbreviation || this.workspace.name, active: true}
      ];
    },
    isActive(item) {
      if (typeof item.to === 'string') {
        return item.to === this.$route.name;
      } else if (typeof item.to === 'object') {
        return item.to.name === this.$route.name;
      }
      return false;
    },
    onScroll() {
      if (this.$refs.workspaceContent.scrollTop >= this.$refs.banner.offsetTop + this.$refs.banner.offsetHeight) {
        this.headerFixed = true;
        this.$refs.headerNav.style.position = 'absolute';
        this.$refs.headerNav.style.left = 0;
        this.$refs.headerNav.style.top = this.$refs.workspaceContent.scrollTop + 'px';
        this.$refs.headerNav.style.width = '100%';
        this.$refs.headerNav.style.zIndex = 10;
        this.$refs.headerNav.style.background = '#FFF';
      } else {
        this.headerFixed = false;
        this.$refs.headerNav.style.position = 'static';
      }
    },
    onInviteClick() {
      this.showInviteModal();
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
#content .workspace-view {
  display: flex;
  flex-direction: row;

  .workspace-content {
    flex: 1;
    display: flex;
    flex-direction: column;
    overflow: auto;

    .content {
      flex: 1;
      //overflow: hidden;
    }
  }

  .workspace-sidebar {
    overflow-y: auto;
  }

  .workspace-sidebar {
    width: 300px;
  }

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

      &.header-fixed {
        padding-top: 50px;

        > .nav {
          .workspace-img-container {
            position: static;
            width: auto;
            height: auto;
            border: none;
            display: flex;
            align-items: center;
            margin-bottom: 0;

            > .nav-link {
              padding: 0 3.5rem;

              img {
                width: 30px;
              }
            }
          }
        }
      }
    }
  }

  .page-content {
    //display: grid;
    //grid-gap: 1em;
    //grid-template-columns: repeat(4, 1fr);
    //
    //.content {
    //  grid-column: 1/4;
    //}
  }
}
</style>