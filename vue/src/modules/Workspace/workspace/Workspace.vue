<template>
  <div id="workspace" class="page">
    <content-spinner :show="loading" :text="$t('Loading...')" class="h-100"/>
    <div class="page-header">
      <b-breadcrumb :items="breadCrumb" class="d-none d-sm-flex"></b-breadcrumb>
      <b-button @click="showModal" variant="info">
        <i class="fas fa-plus-circle"></i>
        {{ $t('Add New Workspace') }}
      </b-button>
    </div>
  </div>
</template>

<script>
import {createNamespacedHelpers} from "vuex";
import ContentSpinner from "../../../core/components/ContentSpinner";

const {mapState} = createNamespacedHelpers('workspace');

export default {
  name: "Workspace",
  components: {ContentSpinner},
  data() {
    return {
      breadCrumb: [
        {
          text: this.$t('My Workspaces')
        }
      ]
    }
  },
  computed: {
    ...mapState(['loading'])
  },
  methods: {
    showModal() {
      console.log('Works')
    },
  },
}
</script>

<style scoped>

</style>