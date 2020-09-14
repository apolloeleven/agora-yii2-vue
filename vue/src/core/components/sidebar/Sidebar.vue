<template>
  <div class="menu">
    <nav>
      <ul>
        <sidebar-item v-for="menuItem in menuItems"
                      :to="menuItem.path || false"
                      :name="menuItem.text"
                      :is-group="menuItem.isGroup"
                      :icon="menuItem.icon"
                      :image="menuItem.image"
                      :link-options="menuItem.linkOptions"
                      :badge="menuItem.badge"
                      :badge-classes="menuItem.badgeClasses"
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
  },
  methods: {
    ...mapActions(['toggleMenuCollapse']),
    ...mapUserActions(['addUserProfileClockCities', 'addUserProfileWeatherCities']),

  },
}
</script>

<style src="./Sidebar.scss" lang="scss">

</style>
