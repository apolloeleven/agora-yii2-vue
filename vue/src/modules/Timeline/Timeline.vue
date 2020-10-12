<template>

</template>

<script>
import {createNamespacedHelpers} from "vuex";

const {mapState, mapActions} = createNamespacedHelpers('timeline');

export default {
  name: "Timeline",
  computed: {
    ...mapState(['loading', 'data'])
  },
  methods: {
    ...mapActions(['getData']),
  },
  created() {
    this.getData();
  },
}
</script>

<style scoped>

</style>