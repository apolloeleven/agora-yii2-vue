<template>
  <div v-if="loading">
    <content-spinner show/>
  </div>
  <div v-else class="workspace-view page">
    <div @scroll="onScroll" ref="workspaceContent" class="workspace-content">
      <div ref="workspaceHeader" class="workspace-header">
        <div ref="banner" class="banner d-flex flex-column align-items-center justify-content-center">
          <div class="workspace-img-container">
            <b-img :src="workspace.image_url || '/assets/logo.png'" fluid class="rounded-0"/>
          </div>
          <div class="workspace-name-container">
            <h1 class="m-0">{{ workspace.name }}</h1>
          </div>
        </div>
        <div class="menu-container" :class="{'header-fixed': headerFixed}">
          <b-nav ref="headerNav" tabs>
            <!--            <b-nav-item class="workspace-img-container">-->
            <!--              <b-img :src="workspace.image_url || '/assets/logo.png'" fluid class="rounded-0"/>-->
            <!--            </b-nav-item>-->
            <b-nav-item :active="isActive(item)" v-for="(item, index) in items" :to="item.to" active-class="active"
                        :key="`workspace-tab-${index}`">
              <i v-if="item.icon" :class="item.icon" class="mr-2"></i>{{ $t(item.title) }}
            </b-nav-item>
            <b-nav-item-dropdown right no-caret>
              <template slot="button-content">
                <i class="fas fa-ellipsis-v"></i>
              </template>
              <b-dropdown-item @click="onInviteClick">
                <i class="fas fa-paper-plane mr-2"/>
                {{ $t('Invite') }}
              </b-dropdown-item>
            </b-nav-item-dropdown>
          </b-nav>
        </div>
      </div>
      <div class="content p-3">
        <router-view/>
      </div>
    </div>

    <b-card class="workspace-sidebar" :title="$t('Contacts')" no-body>
      <b-card-header style="background-color: rgb(32 42 61)" class="text-white">
        <h5 class="card-title mb-0">{{ $t('Contacts') }}</h5>
      </b-card-header>

      <div class="p-3">
        <b-input-group>
          <b-input-group-prepend is-text>
            <i class="fas fa-search"></i>
          </b-input-group-prepend>
          <b-form-input placeholder="Search for contacts"></b-form-input>
        </b-input-group>
      </div>

      <b-list-group style="max-width: 300px;">

        <b-list-group-item v-for="contact in contacts" class="d-flex align-items-start pb-0 pt-3">
          <b-img class="contact-image" :src="contact.avatar"></b-img>
          <div class="contact-content pb-3 border-bottom d-flex flex-column w-100">
            <div class="d-flex justify-content-between">
              <span class="mr-auto">{{ contact.from }}</span>
              <span class="message-time">{{ contact.time | relativeDate }}</span>
            </div>
            <div class="d-flex justify-content-between align-items-start">
              <span class="latest-message">{{ contact.message }}</span>
              <b-badge v-if="contact.unread" pill variant="danger">{{ contact.unread }}</b-badge>
            </div>
          </div>

        </b-list-group-item>
      </b-list-group>
    </b-card>
    <WorkspaceInviteModal/>
  </div>
</template>

<script>
import {createNamespacedHelpers} from "vuex";
import ContentSpinner from "@/core/components/ContentSpinner";
import WorkspaceInviteModal from "./invite/WorkspaceInviteModal";

const {mapState, mapActions} = createNamespacedHelpers('workspace')

export default {
  name: "WorkspaceView",
  components: {WorkspaceInviteModal, ContentSpinner},
  data() {
    return {
      headerFixed: false,
      breadcrumbs: [],
      contacts: [
        {
          id: 1,
          message: 'Lets all be unique together until we realise we are all the same.',
          from: 'Cody Romero',
          avatar: 'https://randomuser.me/api/portraits/men/34.jpg',
          unread: 8,
          time: Date.now() - 1000 * 5
        },
        {
          id: 2,
          message: 'Please wait outside of the house.',
          from: 'Diane Herrera',
          avatar: 'https://randomuser.me/api/portraits/women/6.jpg',
          unread: 3,
          time: Date.now() - 1000 * 55
        },
        {
          id: 3,
          message: 'The book is in front of the table.',
          from: 'Leonard Reyes',
          avatar: 'https://randomuser.me/api/portraits/men/27.jpg',
          unread: 1,
          time: Date.now() - 1000 * 60 * 2
        },
        {
          id: 4,
          message: 'How was the math test?',
          from: 'Alan Austin',
          avatar: 'https://randomuser.me/api/portraits/men/79.jpg',
          unread: 0,
          time: Date.now() - 1000 * 60 * 26
        },
        {
          id: 5,
          message: 'I really want to go to work, but I am too sick to drive.',
          from: 'Scarlett Fernandez',
          avatar: 'https://randomuser.me/api/portraits/women/81.jpg',
          unread: 0,
          time: Date.now() - 1000 * 60 * 55
        },
        {
          id: 6,
          message: 'Christmas is coming.',
          from: 'Larry Stewart',
          avatar: 'https://randomuser.me/api/portraits/men/70.jpg',
          unread: 0,
          time: Date.now() - 1000 * 60 * 60 * 24
        },
        {
          id: 7,
          message: 'She advised him to come back at once.',
          from: 'Thomas Bradley',
          avatar: 'https://randomuser.me/api/portraits/men/16.jpg',
          unread: 0,
          time: Date.now() - 1000 * 60 * 60 * 24 * 5
        },
        {
          id: 8,
          message: 'The lake is a long way from here.',
          from: 'Veronica Brown',
          avatar: 'https://randomuser.me/api/portraits/women/86.jpg',
          unread: 0,
          time: Date.now() - 1000 * 60 * 60 * 24 * 12
        }
      ]
    }
  },
  computed: {
    ...mapState({
      workspace: state => state.view.workspace,
      loading: state => state.view.loading,
    }),
    items() {
      return [
        {title: this.$i18n.t('Timeline'), to: {name: 'workspace.timeline'}, icon: 'fas fa-stream'},
        {
          title: this.$i18n.t('Files'),
          to: {name: 'workspace.files', params: {folderId: this.workspace.rootFolder ? this.workspace.rootFolder.id : ''}},
          icon: 'fa fa-folder'
        },
        {title: this.$i18n.t('Articles'), to: {name: 'workspace.articles'}, icon: 'fa fa-book'},
        {title: this.$i18n.t('Users'), to: {name: 'workspace.users'}, icon: 'fas fa-users'},
        {title: this.$i18n.t('Polls'), to: {name: 'workspace.polls'}, icon: 'fas fa-poll-h'},
        {title: this.$i18n.t('Activity'), to: {name: 'workspace.activity'}, icon: 'fas fa-list'},
        {title: this.$i18n.t('About'), to: {name: 'workspace.about'}, icon: 'fa fa-info-circle'},
      ]
    },
  },
  watch: {
    '$route.params.id': function (id) {
      this.getCurrentWorkspace(id);
    },
  },
  methods: {
    ...mapActions(['getCurrentWorkspace', 'destroyCurrentWorkspace', 'showInviteModal', 'getActiveUsers']),
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
        this.$refs.headerNav.style.position = '';
        this.$refs.headerNav.style.width = '';
      }
    },
    onInviteClick() {
      this.showInviteModal();
    },
  },
  async beforeMount() {
    await this.getCurrentWorkspace(this.$route.params.id);
    this.initBreadcrumbs();
    this.getActiveUsers();
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
    width: 300px;
    box-shadow: -2px 0 5px rgba(0, 0, 0, 0.2);

    .list-group-item {
      cursor: pointer;
      border: none;
      background-color: transparent;
      transition: background-color 0.1s;

      .contact-image {
        border-radius: 50%;
        margin-right: 0.5rem;
        width: 36px;
        height: 36px;
      }

      .message-time,
      .latest-message {
        font-size: 90%;
        color: #727272;
      }

      &:hover {
        background-color: #eeeeee;
      }
    }

    &::-webkit-scrollbar {
      width: 8px;
    }

    /* Track */
    &::-webkit-scrollbar-track {
      background-color: #d6d6d6;
      border-radius: 10px;
    }

    /* Handle */
    &::-webkit-scrollbar-thumb {
      background: rgba(0, 0, 0, 0.2);
      border-radius: 10px;
    }

    /* Handle on hover */
    &::-webkit-scrollbar-thumb:hover {
      background: rgba(0, 0, 0, 0.4);;
    }
  }


  .workspace-header {
    position: relative;
    text-align: center;

    .banner {
      background-color: #3989c6;
      background-size: cover;
      background-position: center;
      background-image: url('https://scontent.ftbs4-1.fna.fbcdn.net/v/t1.0-9/s960x960/53100432_997208287154731_5097617557439905792_o.jpg?_nc_cat=109&ccb=2&_nc_sid=e3f864&_nc_ohc=qVSZCgaIiaYAX8PT8tr&_nc_ht=scontent.ftbs4-1.fna&tp=7&oh=7b72a18af0a2a5be4baf5a1534e887fb&oe=5FDAD07C');
      height: 15rem;
      z-index: 1;
    }

    .workspace-name-container {
      //position: absolute;
      left: 10rem;
      top: 3.5rem;
      color: white;
    }

    .workspace-img-container {
      background-color: white;
      z-index: 2;
      border: 3px solid white;
      border-radius: 50%;
      padding: 20px;
      top: 1.25rem;
      left: 1rem;
      box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.4);
      //position: absolute;

      width: 8rem;
      height: 8rem;
    }

    .menu-container {
      //padding-left: 9rem;
      background-color: white;

      /deep/ .nav {
        display: inline-flex;
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
          justify-content: center;
          box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);

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