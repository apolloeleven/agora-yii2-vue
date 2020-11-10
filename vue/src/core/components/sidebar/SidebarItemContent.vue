<template>
  <div :class="{'text-sm' : isSmaller}">
    <i class="menu-item-icon" :class="[{'text-sm' : isSmaller},icon]"></i>
    <span class="inner-text">{{name}}</span>
    <span v-if="badge" class="badge-wrapper">
        <span class="badge" :class="badgeClasses">{{badge}}</span>
      </span>
  </div>
</template>

<script>
export default {
  name: "SidebarItemContent",
  props: {
    badgeClasses: [String, Array],
    name: String,
    icon: [String, Array],
    badge: Number,
    isSmaller: Boolean,
  }
}
</script>

<style scoped>
.text-sm {
  font-size: .8rem !important;
}
</style>
