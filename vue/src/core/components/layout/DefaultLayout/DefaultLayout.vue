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
    <employee-list-form/>
  </div>
</template>

<script>
import Navbar from './../../navbar/Navbar';
import Sidebar from "./../../sidebar/Sidebar";
import {mapState} from 'vuex';
import UserInvitationForm from "../../../../modules/User/Invitation/UserInvitationForm";
import EmployeeListForm from "../../../../modules/User/Employees/EmployeeListForm";

export default {
  name: "DefaultLayout",
  components: {
    EmployeeListForm,
    Sidebar,
    Navbar,
    UserInvitationForm,
  },
  computed: {
    ...mapState([
      'menuCollapsed',
      'menuHidden'
    ]),
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
    flex-direction: column;
  }

  & /deep/ .page-content {
    overflow: auto;
  }
}
</style>
