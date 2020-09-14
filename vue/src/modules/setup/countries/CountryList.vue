<template>
  <div id="country-list" class="p-3">
    <h1>Countries</h1>

    <b-table striped hover :items="countries.data" :fields="fields"></b-table>
  </div>
</template>

<script>
import {createNamespacedHelpers} from 'vuex';

const {mapActions, mapState} = createNamespacedHelpers('setup');
export default {
  name: "CountryList",
  data() {
    return {
      fields: [
        'name'
      ],
    }
  },
  computed: {
    ...mapState(['countries'])
  },
  methods: {
    ...mapActions(['getCountries'])
  }
}
</script>

<style scoped>

</style>