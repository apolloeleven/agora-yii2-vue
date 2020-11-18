<template>
  <div class="menu">
    <div class="sidebar-header">
      <img class="menu-heading" src="/assets/img/product_logo.png"/>
      Agora Intranet
    </div>
    <nav>
      <ul>
        <sidebar-item v-for="menuItem in menuItems"
                      :to="menuItem.path || false"
                      :name="menuItem.name"
                      :text="menuItem.text"
                      :is-group="menuItem.isGroup"
                      :icon="menuItem.icon"
                      :image="menuItem.image"
                      :link-options="menuItem.linkOptions"
                      :badge="menuItem.badge"
                      :badge-classes="menuItem.badgeClasses"
                      :button-text="menuItem.buttonText"
                      :on-click="menuItem.onClick"
                      :children="menuItem.children"
                      :key="menuItem.path"
        ></sidebar-item>
      </ul>
    </nav>
    <div class="menu-collapse-line">
      <div class="menu-toggle-btn" @click="toggleMenuCollapse" data-action="collapse-expand-sidebar">
        <i class="fas fa-angle-left"></i>
      </div>
    </div>
  </div>
</template>


<script>
import {createNamespacedHelpers, mapGetters, mapActions} from 'vuex';
import SidebarItem from './SidebarItem'
import MenuService from "./MenuService";
import MenuItem from "./MenuItem";

const {mapState: mapStateUser, mapActions: mapUserActions} = createNamespacedHelpers('user');

export default {
  name: "Sidebar",
  components: {
    'sidebar-item': SidebarItem,
  },
  computed: {
    ...mapGetters(['menuItems']),
    ...mapStateUser(['currentUser']),
  },
  created() {
    MenuService.addItem(new MenuItem('favourites', {
      text: this.$t('Short Cuts'),
      isGroup: true,
      weight: 1000,
      icon: 'far fa-star'
    }));
  },
  methods: {
    ...mapActions(['toggleMenuCollapse']),
    ...mapUserActions(['addUserProfileClockCities', 'addUserProfileWeatherCities']),

  },
}
</script>

<style src="./Sidebar.scss" lang="scss">

</style>
