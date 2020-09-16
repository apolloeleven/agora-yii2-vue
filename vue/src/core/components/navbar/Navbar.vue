<template>
  <b-navbar class="header" type="dark" variant="info">
    <b-navbar-brand href="#">
      <div class="navbar-brand-img"
           style="background-image: url('')"></div>
    </b-navbar-brand>

    <SidebarToggle/>

    <div class="navbar-items-2 d-none d-lg-flex">
      <!--TODO permission-->
      <b-navbar-nav>
        <b-nav-item-dropdown :text="$t('Users')">
          <template v-slot:button-content>
            {{ $t('Users') }}
          </template>
          <b-dropdown-item :to="{name: 'user.list'}">
            {{ $t('Employees') }}
          </b-dropdown-item>
          <b-dropdown-item>
            {{ $t('Users - Org Chart') }}
          </b-dropdown-item>
        </b-nav-item-dropdown>
      </b-navbar-nav>
    </div>

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
            <img class="user-avatar" :src="defaultAvatar" alt="..."/>
            <b class="caret"></b>
          </template>
          <b-dropdown-item href="#">Profile</b-dropdown-item>
          <b-dropdown-item @click="onLogoutClick()" href="#">Sign Out</b-dropdown-item>
        </b-nav-item-dropdown>
      </b-navbar-nav>
    </div>
  </b-navbar>
</template>

<script>
import SidebarToggle from './components/SidebarToggle'
import {createNamespacedHelpers} from "vuex";

const {mapState: mapStateUser} = createNamespacedHelpers('user');

export default {
  name: 'Navbar',
  components: {
    SidebarToggle
  },
  data() {
    return {
      messages: [
        {
          id: 1,
          message: 'Lets all be unique together until we realise we are all the same.',
          from: 'Cody Romero',
          avatar: 'https://randomuser.me/api/portraits/men/34.jpg',
          unread: true,
          time: Date.now() - 1000 * 5
        },
        {
          id: 2,
          message: 'Please wait outside of the house.',
          from: 'Diane Herrera',
          avatar: 'https://randomuser.me/api/portraits/women/6.jpg',
          unread: true,
          time: Date.now() - 1000 * 55
        },
        {
          id: 3,
          message: 'The book is in front of the table.',
          from: 'Leonard Reyes',
          avatar: 'https://randomuser.me/api/portraits/men/27.jpg',
          unread: true,
          time: Date.now() - 1000 * 60 * 2
        },
        {
          id: 4,
          message: 'How was the math test?',
          from: 'Alan Austin',
          avatar: 'https://randomuser.me/api/portraits/men/79.jpg',
          unread: true,
          time: Date.now() - 1000 * 60 * 26
        },
        {
          id: 5,
          message: 'I really want to go to work, but I am too sick to drive.',
          from: 'Scarlett Fernandez',
          avatar: 'https://randomuser.me/api/portraits/women/81.jpg',
          unread: false,
          time: Date.now() - 1000 * 60 * 55
        },
        {
          id: 6,
          message: 'Christmas is coming.',
          from: 'Larry Stewart',
          avatar: 'https://randomuser.me/api/portraits/men/70.jpg',
          unread: false,
          time: Date.now() - 1000 * 60 * 60 * 24
        },
        {
          id: 7,
          message: 'She advised him to come back at once.',
          from: 'Thomas Bradley',
          avatar: 'https://randomuser.me/api/portraits/men/16.jpg',
          unread: false,
          time: Date.now() - 1000 * 60 * 60 * 24 * 5
        },
        {
          id: 8,
          message: 'The lake is a long way from here.',
          from: 'Veronica Brown',
          avatar: 'https://randomuser.me/api/portraits/women/86.jpg',
          unread: false,
          time: Date.now() - 1000 * 60 * 60 * 24 * 12
        },
      ],
      notifications: []
    }
  },
  computed: {
    ...mapStateUser(['currentUser', 'defaultAvatar']),
  },
  methods: {
    onLogoutClick() {
      this.$router.push('/auth/login');
    }
  }
}
</script>

<style src="./Navbar.scss" lang="scss">

</style>