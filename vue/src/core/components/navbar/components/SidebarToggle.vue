<template>
  <button class="show-hide-menu" @click="toggleSidebar" :class="{active: menuHidden}">
    <i class="fas fa-bars"></i>
  </button>
</template>

<script>
  import {mapState, mapActions} from 'vuex';

  export default {
    name: "SidebarToggle",
    data: () => {
      return {}
    },
    computed: {
      ...mapState(['menuHidden'])
    },
    methods: {
      ...mapActions(['toggleMenuHide']),
      toggleSidebar() {
        this.toggleMenuHide()
      }
    }
  }
</script>

<style lang="scss" scoped>
  @import '../../../scss/variables';

  .show-hide-menu {
    padding: 0;
    outline: 0;
    border: none;
    text-align: center;
    border-left: 1px solid rgba(0, 0, 0, 0.1);
    border-right: 1px solid rgba(0, 0, 0, 0.1);
    //line-height: $navbar-height;
    width: $navbar-menu-toggle-btn-width;
    background-color: transparent;
    color: white;
    font-size: 18px;

    &:hover {
      background-color: $navbar-items-hover-bg;
    }

    &:active,
    &.active {
      background-color: rgba(0, 0, 0, 0.2);
    }
  }
</style>
