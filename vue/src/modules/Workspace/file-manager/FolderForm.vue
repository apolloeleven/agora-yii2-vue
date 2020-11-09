<template>
  <ValidationObserver ref="form" v-slot="{ handleSubmit, invalid ,reset}">
    <b-modal
      :visible="showModal" id="article-form" ref="modal" size="lg" :title="modalTitle"
      @hidden="hideModal" @ok.prevent="handleSubmit(onSubmit)" :ok-title="$t('Submit')" scrollable>
      <b-form @submit.prevent="handleSubmit(onSubmit)" novalidate>
        <input-widget
          :model="model" attribute="file" type="file" :placeholder="$t('Choose a image or drop it here...')">
        </input-widget>
        <input-widget :model="model" attribute="name"/>
        <input-widget :model="model" attribute="body" type="richtext"/>
      </b-form>
    </b-modal>
  </ValidationObserver>
</template>

<script>
import InputWidget from "../../../core/components/input-widget/InputWidget";
import FolderFormModel from "./FolderFormModel";
import ArticleFormModel from "../article/ArticleFormModel";
import {createNamespacedHelpers} from "vuex";

const {mapState, mapActions} = createNamespacedHelpers('fileManager');
const {mapState: mapWorkspaceState} = createNamespacedHelpers('workspace');
export default {
  name: "FolderForm",
  components: {InputWidget},
  data() {
    return {
      model: new FolderFormModel(),
    }
  },
  computed: {
    ...mapState(['showModal', 'modalFolder', 'currentFolder', 'isFile']),
    ...mapWorkspaceState(['currentWorkspace']),
    modalTitle() {
      if (this.modalFolder) {
        return this.$t(`Update folder '{name}'`, {name: this.modalFolder.title});
      }
      return this.$t(`Create new folder`);
    },
  },
  watch: {
    modalFolder() {
      if (this.modalFolder) {
        this.model = new FolderFormModel(this.modalFolder);
      }
    },
  },
  methods: {
    ...mapActions(['hideFolderModal', 'getFoldersByParent', 'getFoldersByWorkspace', 'createFolder', 'updateFolder']),
    async onSubmit() {
      let action;

      if (this.currentWorkspace.id) {
        this.model.workspace_id = this.currentWorkspace.id;
      }
      this.model.folder_id = this.currentFolder.id;
      this.model.isFile = this.isFile;

      let res
      if (this.model.id) {
        action = 'updated';
        res = await this.updateFolder({...this.model.toJSON()});
      } else {
        action = 'created';
        res = await this.createFolder({...this.model.toJSON()});
      }

      if (this.model.folder_id) {
        this.getFoldersByParent(this.model.folder_id);
      } else if (this.model.workspace_id) {
        this.getFoldersByWorkspace(this.model.workspace_id);
      }

      if (res.success) {
        this.$toast(this.$t(`The folder '{title}' was successfully ${action}`, {title: this.model.name}));
        this.hideModal()
      } else {
        this.model.setMultipleErrors(res.body);
      }
    },
    hideModal() {
      this.hideFolderModal();
      this.model = new FolderFormModel()
    },
  },
}
</script>

<style scoped>

</style>