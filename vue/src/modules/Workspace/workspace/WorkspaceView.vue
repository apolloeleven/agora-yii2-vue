<template>
  <div class="workspace-view page">
    <div class="page-header">
      <back-button/>
      <b-breadcrumb :items="breadCrumb" class="d-none d-sm-flex"></b-breadcrumb>
      <b-button variant="info">
        <i class="fas fa-plus-circle"></i>
        {{ $t('Create new folder') }}
      </b-button>
    </div>
    <div class="content-wrapper p-3">
      <div class="row">
        <div class="col-sm-12 col-lg-6 col-xl-7 mb-4 order-lg-3 col-folders">
          <h4 class="border-bottom pb-1 mb-3">{{ $t('Folders') }}</h4>
        </div>
        <div class="col-sm-12 col-lg-6 col-xl-5 order-lg-2 mb-4 col-timeline">
          <h4 class="border-bottom pb-1 mb-3">{{ $t('Timeline') }}
            <b-button class="float-right" @click="showTimelineModal" size="sm" variant="outline-primary">
              <i class="fas fa-plus-circle"/>
              {{$t('Write on timeline')}}
            </b-button>
          </h4>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import BackButton from "../components/BackButton";
import {createNamespacedHelpers} from "vuex";

const {mapState, mapActions} = createNamespacedHelpers('workspace')
const {mapActions: mapTimelineActions} = createNamespacedHelpers('timeline');

export default {
  name: "WorkspaceView",
  components: {BackButton},
  computed: {
    ...mapState(['breadCrumb']),
  },
  methods: {
    ...mapActions(['getWorkspaceBreadCrumb']),
    ...mapTimelineActions(['showTimelineModal']),
    async getBreadCrumb() {
      const res = await this.getWorkspaceBreadCrumb(this.$route.params.id)
      if (!res.success) {
        this.$toast(this.$t(res.body), 'danger')
        this.$router.push({name: 'workspace'});
      }
    }
  },
  mounted() {
    this.getBreadCrumb()
  }
}
</script>

<style scoped>

</style>