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
    <UserForm/>
    <UserDeleteForm/>
    <UserReadOnlyInformation/>
  </div>
</template>

<script>
import Navbar from './../../navbar/Navbar';
import Sidebar from "./../../sidebar/Sidebar";
import UserForm from "../../../../modules/User/user/UserForm";
import UserDeleteForm from "../../../../modules/User/user/UserDeleteForm";
import UserReadOnlyInformation from "../../../../modules/User/user/UserReadOnlyInformation";
import {mapState} from 'vuex';

export default {
  name: "DefaultLayout",
  components: {
    Sidebar,
    Navbar,
    UserForm,
    UserDeleteForm,
    UserReadOnlyInformation,
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