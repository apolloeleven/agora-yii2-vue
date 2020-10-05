<template>
  <div id="app" class="header-fixed menu-fixed page-header-fixed"
       :class="{'menu-collapsed': this.menuCollapsed, 'menu-hidden': this.menuHidden}">
    <Navbar/>
    <div id="menu-content-wrapper">
      <Sidebar/>
      <div id="content">
        <router-view :key="$route.fullPath"/>
      </div>
    </div>
    <UserInvitationForm/>
    <WorkspaceForm/>
    <ArticleForm/>
  </div>
</template>

<script>
import Navbar from './../../navbar/Navbar';
import Sidebar from "./../../sidebar/Sidebar";
import UserInvitationForm from "../../../../modules/User/Invitation/UserInvitationForm";
import WorkspaceForm from "../../../../modules/Workspace/workspace/WorkspaceForm";
import ArticleForm from "../../../../modules/Workspace/article/ArticleForm";
import {mapState, createNamespacedHelpers} from 'vuex';
import MenuService from "../../sidebar/MenuService";
import MenuItem from "../../sidebar/MenuItem";

const {mapActions} = createNamespacedHelpers('user');
const {mapState: mapStateWorkspace, mapActions: mapActionsWorkspace} = createNamespacedHelpers('workspace');

export default {
  name: "DefaultLayout",
  components: {
    Sidebar,
    Navbar,
    UserInvitationForm,
    WorkspaceForm,
    ArticleForm,
  },
  computed: {
    ...mapState([
      'menuCollapsed',
      'menuHidden'
    ]),
    ...mapStateWorkspace(['workspaces'])
  },
  watch: {
    workspaces() {
      const menuItems = MenuService.getItems();
      menuItems.forEach(menuItem => {
        if (menuItem.name.indexOf('workspace-') === 0) {
          MenuService.removeItem(menuItem.name)
        }
      });
      this.workspaces.forEach(function (w, i) {
        MenuService.removeItem(`/workspace/${w.id}`);
        MenuService.addItem(new MenuItem(`workspace-${w.id}`, {
          text: w.name,
          path: `/workspace/${w.id}`,
          weight: 100 + i,
          icon: 'fas fa-home',
          linkOptions: {
            'class': 'pl-4'
          }
        }))
      })
    },
  },
  methods: {
    ...mapActions(['getProfile']),
    ...mapActionsWorkspace(['getWorkspaces']),
  },
  created() {
    this.getWorkspaces();
  },
  mounted() {
    this.getProfile();
  }
}
</script>

<style lang="scss" scoped>
@import "../../../scss/variables";

#app {
  height: 100%;
  display: flex;
  flex-direction: column;
  overflow: hidden;
}

#menu-content-wrapper {
  flex: 1;
  display: flex;
  overflow: hidden;
}

#content {
  flex: 1;
  background-color: #f1f1f1;
  position: relative;
  overflow: hidden;
  display: flex;
  flex-direction: column;

  & /deep/ .page {
    overflow: hidden;
    display: flex;
    flex: 1;
    flex-direction: column;
  }

  & /deep/ .page-content {
    overflow: auto;
    flex: 1;
  }
}
</style>
