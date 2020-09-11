<template>
  <div class="menu">
    <nav>
      <ul>
        <sidebar-item v-for="(menuItem, index) in menuData"
                      :to="menuItem.path || false"
                      :name="menuItem.text"
                      :icon="menuItem.icon"
                      :badge="menuItem.badge"
                      :badge-classes="menuItem.badgeClasses"
                      :children="menuItem.children"
                      :key="index"
        ></sidebar-item>
      </ul>
    </nav>
    <div class="menu-collapse-line">
      <div class="menu-toggle-btn" @click="sidebarCollapseToggle" data-action="collapse-expand-sidebar">
        <font-awesome-icon :icon="['fas', 'angle-left']"></font-awesome-icon>
      </div>
    </div>
  </div>
</template>


<script>
  import {mapActions} from 'vuex';
  import SidebarItem from './SidebarItem'
  import menuService from './MenuService';

  export default {
    name: "Sidebar",
    components: {
      'sidebar-item': SidebarItem
    },
    data: () => {
      return {
        menuData: menuService.getItems()
      }
    },
    methods: {
      ...mapActions(['toggleMenuCollapse']),
      sidebarCollapseToggle() {
        this.toggleMenuCollapse();
      }
    }
  }
</script>

<style src="./Sidebar.scss" lang="scss">

</style>
