<template>
  <div style="align-content: center; width: 100%">
    <content-spinner :show="loading"/>
    <vo-basic v-if="chartData" :data="chartData" :verticalDepth="3" :pan="true" :zoom="true"></vo-basic>
    <no-data-available v-else/>
  </div>
</template>

<script>
import {VoBasic} from '../../core/components/org-chart/src/index'
import orgchartService from "@/modules/Orgchart/OrgchartService";
import ContentSpinner from "@/core/components/ContentSpinner";
import NoDataAvailable from "@/core/components/NoDataAvailable";

export default {
  name: "OrgchartBody",
  components: {NoDataAvailable, ContentSpinner, VoBasic},
  data() {
    return {
      chartData: {},
      loading: false
    }
  },
  props: {
    country: {
      type: Object,
      required: true
    }
  },
  methods: {
    async getChartData() {
      this.loading = true;
      let response = await orgchartService.getOrgChartData(this.country.id);
      if (response.success) {
        this.chartData = response.body;
      }
      this.loading = false;
    }
  },
  beforeMount() {
    this.getChartData();
  }
}
</script>

<style scoped>

</style>