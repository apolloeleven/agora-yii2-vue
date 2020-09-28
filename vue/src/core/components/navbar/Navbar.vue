<template>
  <b-navbar class="header" type="dark" variant="info">
    <b-navbar-brand href="#">
      <div class="navbar-brand-img"
           style="background-image: url('')"></div>
    </b-navbar-brand>

    <SidebarToggle/>

    <b-nav-form class="navbar-search">
      <label for="search" class="sr-only">Search...</label>
      <input type="text" name="search" id="search" placeholder="Search...">
      <a class="btn btn-search">
        <span class="glyphicon glyphicon-search"></span>
      </a>
      <a class="btn btn-remove">
        <span class="glyphicon glyphicon-remove"></span>
      </a>

      <!--      <b-form-input class="mr-sm-2" placeholder="Search"></b-form-input>-->
      <!--      <b-button class="my-2 my-sm-0" type="submit">Search</b-button>-->
    </b-nav-form>
    <!--    <div class="clearfix-xxs"></div>-->
    <div class="navbar-items ml-auto">
      <b-navbar-nav class="user-actions">
        <!--    Avatar -->
        <b-nav-item-dropdown right>
          <!-- Using 'button-content' slot -->
          <template v-slot:button-content>
            <b-img class="user-avatar mr-2" :src="currentUser.data.image_url" rounded="circle" alt="..."/>
            {{ currentUser.data.display_name }}
            <b class="caret"></b>
          </template>
          <b-dropdown-item :to="{name: 'profile'}">{{ $t('Profile') }}</b-dropdown-item>
          <b-dropdown-item @click="onLogoutClick()" href="#">{{ $t('Sign Out') }}</b-dropdown-item>
        </b-nav-item-dropdown>
      </b-navbar-nav>
    </div>
  </b-navbar>
</template>

<script>
import SidebarToggle from './components/SidebarToggle'
import {createNamespacedHelpers} from "vuex";

const {mapState} = createNamespacedHelpers('user');
export default {
  name: 'Navbar',
  components: {
    SidebarToggle
  },
  data() {
    return {}
  },
  computed: {
    ...mapState(['currentUser'])
  },
  methods: {
    onLogoutClick() {
      this.$router.push({name: 'auth.login'});
    }
  }
}
</script>

<style src="./Navbar.scss" lang="scss">

</style>
