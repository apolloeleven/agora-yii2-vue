<template>
  <ValidationObserver ref="form" v-slot="{ handleSubmit, invalid ,reset}">
    <b-modal
        :visible="showModal" id="workspace-form" ref="modal"
        :title='model.id ? $t(`Update workspace "{workspace}"`,{workspace:model.name}) : $t(`Add New Workspace`)'
        @hidden="hideModal" @ok.prevent="handleSubmit(onSubmit)" :ok-disabled="loading" :ok-title="$t('Submit')"
        scrollable>
      <!--<content-spinner :show="loading" :text="$t('Please wait...')" :fullscreen="true" class="h-100"/>-->
      <b-form @submit.prevent="handleSubmit(onSubmit)" novalidate>
        <input-widget :model="model" attribute="folder_in_folder" type="checkbox"/>
        <input-widget :model="model" attribute="name"></input-widget>
        <input-widget :model="model" attribute="abbreviation"></input-widget>
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
      action: '',
    }
  },
  computed: {
    ...mapState(['loading', 'showModal']),
  },
  methods: {
    ...mapActions(['hideWorkspaceModal', 'createWorkspace',]),
    async onSubmit() {
      let res

      this.model.folder_in_folder = this.model.folder_in_folder ? 1 : 0;

      this.action = 'created';
      res = await this.createWorkspace(this.model);
      if (res.success) {
        this.$toast(this.$t(`The workspace '{name}' was successfully ${this.action}`, {name: this.model.name}));
        this.hideWorkspaceModal()
        this.model = new WorkspaceFormModel()
      } else {
        this.$toast(this.$t(`The workspace '{name}' was not ${this.action}`, {name: this.model.name}), 'danger');
        this.hideWorkspaceModal()
        this.model = new WorkspaceFormModel()
      }
    },
    hideModal() {
      this.hideWorkspaceModal()
    }
  }
}
</script>

<style scoped>

</style>