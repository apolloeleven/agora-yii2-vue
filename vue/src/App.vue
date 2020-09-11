<template>
  <div id="app" :class="{'menu-collapsed': this.menuCollapsed, 'menu-hidden': this.menuHidden}">
    <transition name="fade" mode="out-in">
      <router-view/>
    </transition>
  </div>
  <!--  <div id="app" :class="{'menu-hidden': !menuOpened, 'menu-collapsed': menuCollapsed}">-->
  <!--    <navbar></navbar>-->
  <!--    <sidebar />-->
  <!--    <div id="main">-->
  <!--      <div id="ribbon" class="hidden-print">-->
  <!--        <a href="#dashboard" class="btn-ribbon" data-container="#main" data-toggle="tooltip"-->
  <!--           data-title="Show dashboard"><i-->
  <!--                class="fa fa-home"></i></a>-->
  <!--        <span class="vertical-devider">&nbsp;</span>-->
  <!--        <button class="btn-ribbon" data-container="#main" data-action="reload" data-toggle="tooltip"-->
  <!--                data-title="Reload content by ajax"><i class="fa fa-refresh"></i></button>-->
  <!--        <ol class="breadcrumb">-->
  <!--        </ol>-->
  <!--      </div>-->
  <!--      <div id="content">-->
  <!--        <router-view/>-->
  <!--      </div>-->
  <!--    </div>-->
  <!--  </div>-->
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
  computed: mapState([
    'menuCollapsed',
    'menuHidden'
  ]),
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
#app {
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
</style>
