<template>
  <ValidationObserver ref="form" v-slot="{ handleSubmit, invalid ,reset}">
    <b-modal
        :visible="showModal" id="workspace-form" ref="modal"
        :title='model ? $t(  `Update workspace "{workspace}"`,{workspace:model.name}) : $t( `Add New Workspace`)'
        @hidden="hideModal" @ok.prevent="handleSubmit(onSubmit)" :ok-disabled="loading" :ok-title="$t('Submit')"
        scrollable>
      <!--<content-spinner :show="loading" :text="$t('Please wait...')" :fullscreen="true" class="h-100"/>-->
      <b-form @submit.prevent="handleSubmit(onSubmit)" novalidate>
        <input-widget :model="model" attribute="name"></input-widget>
      </b-form>
    </b-modal>
  </ValidationObserver>
</template>

<script>
import WorkspaceFormModel from "./WorkspaceFormModel";
import InputWidget from "../../../core/components/input-widget/InputWidget";
import {createNamespacedHelpers} from "vuex";
import ContentSpinner from "../../../core/components/ContentSpinner";

const {mapState, mapActions} = createNamespacedHelpers('workspace');

export default {
  name: "WorkspaceForm",
  components: {ContentSpinner, InputWidget},
  data() {
    return {
      model: new WorkspaceFormModel(),
    }
  },
  computed: {
    ...mapState(['loading', 'showModal']),
  },
  methods: {
    ...mapActions(['hideWorkspaceModal']),
    async onSubmit() {

    },
    hideModal() {
      this.hideWorkspaceModal()
    }
  }
}
</script>

<style scoped>

</style>