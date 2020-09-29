<template>
  <div id="app-wrapper" :class="'route-'+routeName">
    <transition name="fade" mode="out-in">
      <router-view/>
    </transition>
    <notifications position="top center" group="error" :type="'error'" width="400" :duration="5000"/>
    <notifications group="success" type="success" :duration="5000" width="400" position="top right"></notifications>
  </div>
</template>

<script>
import {eventBus} from './core/services/event-bus'
import {mapState} from 'vuex';

export default {
  name: 'App',
  components: {},
  data: () => {
    return {
      menuOpened: true,
    }
  },
  computed: {
    ...mapState([
      'menuCollapsed',
      'menuHidden'
    ]),

    routeName() {
      if (this.$route.name) {
        return this.$route.name.replace('.', '-');
      }
      return '';
    }

  },
  created() {
    eventBus.$on('sidebarShowHideToggled', (opened) => {
      this.menuOpened = opened
    })
    eventBus.$on('sidebarCollapseToggled', (collapsed) => {
      this.menuCollapsed = collapsed
    })
  }
}
</script>

<style lang="scss">
@import "./core/scss/variables";

html,
body,
#app-wrapper {
  height: 100vh;
}

.fade-enter-active,
.fade-leave-active {
  transition-duration: 0.3s;
  transition-property: opacity;
  transition-timing-function: ease;
}

.fade-enter,
.fade-leave-active {
  opacity: 0
}

.vue-notification-group {
  margin: 15px;
  .vue-notification {
    cursor: pointer;
    box-shadow: 0px 0px 5px rgba(0, 0, 0, 0.5);
  }
}
</style>
