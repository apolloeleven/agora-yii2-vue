<template>
  <div class="page-content p-3">
    <div class="sub-menu">
      <b-list-group>
        <b-list-group-item :active="isActive(item)" v-for="(item, index) in items" :to="item.to"
                           :key="`sided-nav-item-${item.title}-${index}`">
          <i v-if="item.icon" :class="item.icon" class="mr-2"></i> {{ item.title }}
        </b-list-group-item>
      </b-list-group>
    </div>
    <div class="content">
      <router-view/>
    </div>
  </div>
</template>

<script>
export default {
  name: "SidedNavLayout",
  props: {
    items: {
      type: Array,
      required: true
    }
  },
  methods: {
    isActive(item) {
      if (typeof item.to === 'string') {
        return item.to === this.$route.name;
      } else if (typeof item.to === 'object') {
        if (this.$route.name === 'workspace.folder') {
          return item.to.name === 'workspace.files';
        }
        return item.to.name === this.$route.name;
      }
      return false;
    }
  }
}
</script>

<style lang="scss" scoped>
.page-content {
  display: grid;
  grid-gap: 1em;
  grid-template-columns: repeat(6, 1fr);

  .content {
    grid-column: 2/7;
  }
}
</style>