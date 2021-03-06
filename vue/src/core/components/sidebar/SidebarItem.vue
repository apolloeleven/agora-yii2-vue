<template>
  <li v-if="isGroup" class="menu-items-header">
    <div>
      <i v-if="icon" class="menu-item-icon" :class="icon"></i>
      <span class="inner-text">{{ text }}</span>
    </div>
    <span class="btn" v-if="buttonText" @click="onClick" v-html="buttonText">
    </span>
  </li>
  <router-link v-else :to="to || '#'" tag="li" active-class="active" :class="{opened: opened}">
    <a :class="linkOptions.class" @click="onMenuItemClick">
      <sidebar-item-content :image="image" :icon="icon" :text="text" :name="name" :badge="badge"
                            :badge-classes="badgeClasses"></sidebar-item-content>
      <span v-if="children && children.length" class="menu-item-toggle-icon " @click="toggleItem($event)">
          <i v-if="level === 1" class="fa fa-chevron-circle-right"></i>
          <i v-else-if="!opened" class="fa fa-plus-square-o"></i>
          <i v-else class="fa fa-minus-square-o "></i>
        </span>
    </a>
    <ul :style="subItemsStyle" v-if="children && children.length">
      <sidebar-item v-for="(childItem, i) in children"
                    :to="childItem.path || false"
                    :is-group="childItem.isGroup"
                    :name="childItem.name"
                    :text="childItem.text"
                    :icon="childItem.icon"
                    :image="childItem.image"
                    :link-options="childItem.linkOptions"
                    :badge="childItem.badge"
                    :badge-classes="childItem.badgeClasses"
                    :button-text="buttonText"
                    :on-click="onClick"
                    :children="childItem.children"
                    :level="childItem.level + 1"
                    :key="i"
      ></sidebar-item>
    </ul>
  </router-link>
</template>

<script>
import SidebarItemContent from './SidebarItemContent'

import {mapActions} from 'vuex';

export default {
  name: "SidebarItem",
  props: {
    to: [String, Object, Boolean],
    name: {
      type: String,
      required: true
    },
    text: {
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
    isGroup: Boolean,
    buttonText: String,
    onClick: {
      type: [Function, null]
    },
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
    toggleItem($event) {
      $event.preventDefault();
      $event.stopPropagation();
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
