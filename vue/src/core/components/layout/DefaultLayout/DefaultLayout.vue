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
    <employee-form-modal/>
    <WorkspaceForm/>
    <ArticleForm/>
    <TimelineForm/>
    <TimelineShare/>
  </div>
</template>

<script>
import Navbar from './../../navbar/Navbar';
import Sidebar from "./../../sidebar/Sidebar";
import WorkspaceForm from "@/modules/Workspace/WorkspaceForm";
import {mapState, createNamespacedHelpers, mapActions} from 'vuex';
import MenuService from "../../sidebar/MenuService";
import MenuItem from "../../sidebar/MenuItem";
import EmployeeFormModal from "@/modules/setup/employees/EmployeeFormModal";
import TimelineForm from "@/modules/Workspace/view/timeline/TimelineForm";
import TimelineShare from "@/modules/Workspace/view/timeline/TimelineShare";
import ArticleForm from "@/modules/Workspace/view/articles/ArticleForm";

const {mapActions: userMapActions} = createNamespacedHelpers('user');
const {mapState: mapStateWorkspace, mapActions: mapActionsWorkspace} = createNamespacedHelpers('workspace');

export default {
  name: "DefaultLayout",
  components: {
    ArticleForm,
    TimelineShare,
    TimelineForm,
    EmployeeFormModal,
    Sidebar,
    Navbar,
    WorkspaceForm,
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
    ...userMapActions(['getProfile']),
    ...mapActionsWorkspace(['getWorkspaces']),
    ...mapActions(['initGlobals'])
  },
  created() {
    this.getWorkspaces();
  },
  mounted() {
    this.getProfile();
  },
  beforeMount() {
    this.initGlobals();
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
