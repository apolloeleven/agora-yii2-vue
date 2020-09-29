<template>
  <ValidationObserver ref="form" v-slot="{ handleSubmit, invalid ,reset}">
    <b-modal
        :visible="showModal" id="workspace-form" ref="modal"
        :title='modalWorkspace ? $t(`Update workspace "{workspace}"`,{workspace:model.name}) : $t(`Add New Workspace`)'
        @hidden="hideModal" @ok.prevent="handleSubmit(onSubmit)" :ok-disabled="loading" :ok-title="$t('Submit')"
        scrollable>
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

const {mapState, mapActions} = createNamespacedHelpers('workspace');

export default {
  name: "WorkspaceForm",
  components: {InputWidget},
  data() {
    return {
      model: new WorkspaceFormModel(),
      action: '',
    }
  },
  computed: {
    ...mapState(['loading', 'showModal', 'modalWorkspace']),
  },
  watch: {
    modalWorkspace() {
      const w = this.modalWorkspace;
      if (w) {
        this.model.id = w.id;
        this.model.name = w.name;
        this.model.abbreviation = w.abbreviation;
        // this.model.image_url= w.image_url;
        // this.model.description= w.description;
        this.model.folder_in_folder = !!w.folder_in_folder;
      } else {
        this.model = new WorkspaceFormModel()
      }
    }
  },
  methods: {
    ...mapActions(['hideWorkspaceModal', 'createWorkspace', 'updateWorkspace']),
    async onSubmit() {
      this.model.folder_in_folder = this.model.folder_in_folder ? 1 : 0;

      let res
      if (this.model.id) {
        this.action = 'updated';
        res = await this.updateWorkspace(this.model);
      } else {
        this.action = 'created';
        res = await this.createWorkspace(this.model);
      }
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