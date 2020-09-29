<template>
  <div class="workspace-view page">
    <div class="page-header">
      <back-button/>
      <b-breadcrumb class="d-none d-sm-flex"></b-breadcrumb>
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
          <h4 class="border-bottom pb-1 mb-3">{{ $t('Timeline') }}</h4>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import BackButton from "../components/BackButton";

export default {
  name: "WorkspaceView",
  components: {BackButton},
}
</script>

<style scoped>

</style>