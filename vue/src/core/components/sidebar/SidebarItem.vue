<template>
  <li v-if="isGroup" class="menu-items-header">
    <sidebar-item-content :icon="icon" :name="name" :badge="badge"
                          :badge-classes="badgeClasses"></sidebar-item-content>
  </li>
  <router-link v-else-if="to" :to="to" tag="li" active-class="active" :exact="true">
    <a :class="linkOptions.class" @click="onMenuItemClick">
      <sidebar-item-content :image="image" :icon="icon" :name="name" :badge="badge"
                            :badge-classes="badgeClasses"></sidebar-item-content>
    </a>
  </router-link>
  <li v-else :class="{opened: opened}">
    <a href="#" @click="toggleItem()" :class="linkOptions.class">
      <sidebar-item-content :icon="icon" :name="name" :badge="badge"
                            :badge-classes="badgeClasses"></sidebar-item-content>
      <template v-if="children && children.length">
        <i v-if="level === 1" class="fa fa-chevron-circle-right menu-item-toggle-icon"></i>
        <i v-else-if="!opened" class="fa fa-plus-square-o menu-item-toggle-icon"></i>
        <i v-else class="menu-item-toggle-icon fa fa-minus-square-o"></i>
      </template>
    </a>
    <ul :style="subItemsStyle" v-if="children && children.length">
      <sidebar-item v-for="(childItem, i) in children"
                    :to="childItem.path || false"
                    :is-group="childItem.isGroup"
                    :name="childItem.name"
                    :icon="childItem.icon"
                    :image="childItem.image"
                    :link-options="childItem.linkOptions"
                    :badge="childItem.badge"
                    :badge-classes="childItem.badgeClasses"
                    :children="childItem.children"
                    :level="childItem.level + 1"
                    :key="i"
      ></sidebar-item>
    </ul>
  </li>
</template>

<script>
import SidebarItemContent from './SidebarItemContent'

import {createNamespacedHelpers} from 'vuex';

const {mapActions} = createNamespacedHelpers('app');

export default {
  name: "SidebarItem",
  props: {
    to: [String, Object, Boolean],
    name: {
      type: String,
      required: true
    },
    level: {
      type: Number,
      default: 1
    },
    icon: [String, Array],
    image: String,
    linkOptions: {
      type: Object,
      default() {
        return {}
      }
    },
    badge: Number,
    badgeClasses: [String, Array],
    children: Array,
    isGroup: Boolean
  },
  components: {
    SidebarItemContent
  },
  data: () => {
    return {
      opened: false
    }
  },
  computed: {
    subItemsStyle() {
      return {
        height: this.opened ? 'auto' : '0px',
        display: this.opened ? 'block' : 'none'
      }
    }
  },
  methods: {
    ...mapActions(['toggleMenuHide']),
    toggleItem() {
      if (this.children && this.children.length) {
        this.opened = !this.opened
      }
    },
    onMenuItemClick() {
      if (window.outerWidth < 768) {
        this.toggleMenuHide();
      }
    }
  }
}
</script>

<style scoped>

</style>
