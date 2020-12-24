<template>
  <div v-if="currentUser.loaded" id="app" class="header-fixed menu-fixed page-header-fixed"
       :class="{'menu-collapsed': this.menuCollapsed, 'menu-hidden': this.menuHidden}">
    <Sidebar/>
    <div id="menu-content-wrapper">
      <Navbar/>
      <div id="content">
        <router-view/>
      </div>
    </div>
    <employee-form-modal/>
    <WorkspaceForm/>
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
import i18n from "@/shared/i18n";

const {mapActions: mapActionsEmployee} = createNamespacedHelpers('employee');
const {mapState: mapUserState, mapActions: userMapActions} = createNamespacedHelpers('user');
const {mapState: mapStateWorkspace, mapActions: mapActionsWorkspace} = createNamespacedHelpers('workspace');

export default {
  name: "DefaultLayout",
  components: {
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
    ...mapUserState(['currentUser']),
    ...mapStateWorkspace(['workspaces'])
  },
  watch: {
    workspaces() {
      MenuService.addItem(new MenuItem('allWorkspaces', {
        text: i18n.t('Workspaces'),
        weight: 90,
        isGroup: true,
        buttonText: '<i class="fas fa-plus"></i> ' + i18n.t('New'),
        onClick: () => this.showModal()
      }));
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
        }))
      })
    },
  },
  methods: {
    ...userMapActions(['getProfile']),
    ...mapActionsWorkspace(['getWorkspaces', 'showWorkspaceModal']),
    ...mapActions(['initGlobals']),
    ...mapActionsEmployee(['getModalDropdownData']),
    showModal() {
      this.showWorkspaceModal(null)
    },
  },
  created() {
    this.getWorkspaces();
  },
  mounted() {
    this.getProfile();
  },
  beforeMount() {
    this.getModalDropdownData();
    this.initGlobals();
  }
}
</script>

<style lang="scss" scoped>
@import "../../../scss/variables";

#app {
  height: 100%;
  display: flex;
  //flex-direction: column;
  overflow: hidden;
}

#menu-content-wrapper {
  flex: 1;
  display: flex;
  flex-direction: column;
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
