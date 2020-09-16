<template>
  <a @click="showReadOnlyForm" slot="full-name" href="javascript:void(0)">
    <slot v-if="$slots.default"></slot>
    <template v-else>
      <span v-html="user.displayName"> </span>
    </template>
  </a>
</template>

<script>

import {createNamespacedHelpers} from "vuex";

const {mapActions} = createNamespacedHelpers('user');


export default {
  name: "UserViewLink",
  props: {
    user: Object
  },
  methods: {
    ...mapActions(['showForm']),
    showReadOnlyForm() {
      this.showForm(this.user);
    },
  }
}

</script>

<style lang="scss" scoped>


</style>
