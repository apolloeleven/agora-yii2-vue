<template>
  <router-link v-if="to" :to="to" tag="li" active-class="active" :exact="true">
    <a>
      <sidebar-item-content :icon="icon" :name="name" :badge="badge" :badge-classes="badgeClasses"></sidebar-item-content>
    </a>
  </router-link>
  <li v-else :class="{opened: opened}" >
    <a href="#" @click="toggleItem()">
      <sidebar-item-content :icon="icon" :name="name" :badge="badge" :badge-classes="badgeClasses"></sidebar-item-content>
      <template v-if="children && children.length">
        <i v-if="level === 1" class="fa fa-chevron-circle-right menu-item-toggle-icon"></i>
        <i v-else-if="!opened" class="fa fa-plus-square-o menu-item-toggle-icon"></i>
        <i v-else class="menu-item-toggle-icon fa fa-minus-square-o"></i>
      </template>
    </a>
    <ul :style="subItemsStyle" v-if="children && children.length">
      <pre>{{children}}</pre>
      <sidebar-item v-for="(childItem, i) in children"
                    :to="childItem.url"
                    :name="childItem.name"
                    :icon="childItem.icon"
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
    badge: Number,
    badgeClasses: [String, Array],
    children: Array
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
    subItemsStyle () {
      return {
        height: this.opened ? 'auto' : '0px',
        display: this.opened ? 'block' : 'none'
      }
    }
  },
  methods: {
    toggleItem () {
      if (this.children && this.children.length) {
        this.opened = !this.opened
      }
    }
  }
}
</script>

<style scoped>

</style>
