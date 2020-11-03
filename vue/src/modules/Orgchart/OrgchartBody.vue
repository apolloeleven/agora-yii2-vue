<template>
  <div style="align-content: center; width: 100%">
    <content-spinner :show="loading"/>
    <vo-basic :data="chartData" :verticalDepth="3" :pan="true" :zoom="true"></vo-basic>
  </div>
</template>

<script>
import {VoBasic} from '../../core/components/org-chart/src/index'
import orgchartService from "@/modules/Orgchart/OrgchartService";
import ContentSpinner from "@/core/components/ContentSpinner";

export default {
  name: "OrgchartBody",
  components: {ContentSpinner, VoBasic},
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