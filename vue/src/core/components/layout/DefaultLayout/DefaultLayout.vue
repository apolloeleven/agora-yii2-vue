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
  </div>
</template>

<script>
import Navbar from './../../navbar/Navbar';
import Sidebar from "./../../sidebar/Sidebar";
import {mapState, createNamespacedHelpers} from 'vuex';

const {mapActions} = createNamespacedHelpers('user');

export default {
  name: "DefaultLayout",
  components: {
    Sidebar,
    Navbar,
  },
  computed: {
    ...mapState([
      'menuCollapsed',
      'menuHidden'
    ]),
  },
  methods: {
    ...mapActions(['getProfile'])
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
