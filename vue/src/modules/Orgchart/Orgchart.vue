<template>
  <no-data-available v-if="!countries || countries.length === 0"/>
  <b-tabs v-else content-class="mt-3">
    <b-tab v-for="(country,index) in countries" :key="`org-chart-country-tab-${index}`" :title="country.name" lazy>
      <orgchart-body :country="country"/>
    </b-tab>
  </b-tabs>
</template>

<script>

import {createNamespacedHelpers} from "vuex";
import NoDataAvailable from "@/core/components/NoDataAvailable";
import OrgchartBody from "@/modules/Orgchart/OrgchartBody";

const {mapState} = createNamespacedHelpers('setup');

export default {
  name: "orgchart",
  components: {OrgchartBody, NoDataAvailable},
  computed: {
    ...mapState({
      countries: state => state.countries.data
    })
  }
}
</script>

<style scoped>

</style>