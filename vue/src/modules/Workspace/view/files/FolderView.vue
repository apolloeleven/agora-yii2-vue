<template>
  <div class="file-manager">
    <FolderItems :model="foldersAndFiles" :fields="fields" :selected="selected" @onFileChoose="onFileChoose"
                 @showModal="showModal" @editClicked="editClicked" @onDeleteMultipleFiles="onDeleteMultipleFiles"
                 @removeClicked="removeClicked"/>
    <FolderForm/>
  </div>
</template>

<script>

import {createNamespacedHelpers} from "vuex";
import FolderForm from "./FolderForm";
import FolderItems from "./FolderItems";

const {mapState: mapWorkspaceState, mapActions: mapWorkspaceActions} = createNamespacedHelpers('workspace');

export default {
  name: "FolderView",
  components: {FolderItems, FolderForm},
  computed: {
    ...mapWorkspaceState({
      foldersAndFiles: state => state.view.folders.folderAndFiles,
      currentFolder: state => state.view.folders.folder,
      attachConfig: state => state.view.folders.attachConfig,
    }),
    fields() {
      return [
        {key: 'checkbox', label: ''},
        {key: 'name', class: 'name', sortable: true},
        {key: 'size', class: 'size', sortable: true},
        {key: 'updated_at', sortable: true,},
        {key: 'updatedBy.displayName', label: this.$i18n.t('Updated By'), sortable: true},
        {key: 'actions', class: 'text-center'},
      ]
    },
    selected() {
      return this.foldersAndFiles.filter(a => a.selected === '1')
    },
  },
  watch: {
    '$route.params.folderId': function (id) {
      this.getCurrentFolder(id);
      this.getFoldersByParent(id);
    }
  },
  methods: {
    ...mapWorkspaceActions(['showFolderModal', 'getFoldersByParent', 'attachFiles', 'getAttachConfig',
      'getCurrentFolder', 'deleteFolder', 'destroyedCurrentFolder']),
    showModal() {
      this.showFolderModal(null)
    },
    editClicked(e) {
      this.showFolderModal(e)
    },
    onDeleteMultipleFiles() {
      this.showDeleteConfirmation(this.selected.map(a => a.id))
    },
    async showDeleteConfirmation(fileIds) {
      const result = await this.$confirm(
        this.$t('You are about to delete {count} file(s) and folder(s).' +
          ' Are you sure you want to continue?', {count: fileIds.length}),
        this.$t('This operation can not be undone'))
      if (result) {
        const res = await this.deleteFolder(fileIds);
        if (res.success) {
          this.$toast(this.$t(`{count} file(s) and folder(s) were successfully deleted`, {count: fileIds.length}));
        } else {
          this.$toast(res.body.message, 'danger');
        }
      }
    },
    removeClicked(e) {
      this.showDeleteConfirmation([e.id])
    },
    checkFileNames(fileNames) {
      return this.foldersAndFiles.filter(f => fileNames.includes(f.name)).map(f => f.name);
    },
    async onFileChoose(ev) {
      const filesArray = [];
      let filesName = [];

      let maxFileSize = this.attachConfig.upload_max_filesize.size;
      const maxFileUploads = parseInt(this.attachConfig.max_file_uploads.size);

      let maxSizeInMb;
      if (maxFileSize.includes('M')) {
        maxSizeInMb = parseInt(maxFileSize.split('M')[0]);
      } else if (maxFileSize.includes('G')) {
        maxSizeInMb = parseInt(maxFileSize.split('G')[0]) * 1024;
      }

      let isFileTooBig = false;
      let bigFile = '';
      for (let i = 0; i < ev.target.files.length; i++) {
        let fileSizeInMb = ev.target.files[i].size / 1000000;
        filesArray.push(ev.target.files[i]);
        filesName.push(ev.target.files[i].name);

        if (fileSizeInMb > maxSizeInMb) {
          isFileTooBig = true;
          bigFile = ev.target.files[i].name
        }
      }
      if (filesArray.length > maxFileUploads) {
        this.$toast(this.$t(`Too much files for upload at same time`), 'danger');
      } else if (isFileTooBig) {
        this.$toast(this.$t(`File '{name}' is too big for upload`, {name: bigFile}), 'danger');
      } else {
        let checkFiles = this.checkFileNames(filesName);

        if (checkFiles[0]) {
          const result = await this.$confirm(
            this.$t(`{file_names} file already exist. Are you sure that you want to overwrite them?`, {file_names: `${checkFiles.join(',')}`}),
            this.$t('Filename conflicts'))
          if (result) {
            this.filesAttach(ev, this.$route.params.folderId, filesArray)
          }
        } else {
          this.filesAttach(ev, this.$route.params.folderId, filesArray);
        }
      }
    },
    async filesAttach(ev, workspaceId, filesArray) {
      const filesObject = {
        folder_id: workspaceId,
        files: filesArray,
        isFile: true,
      };
      const res = await this.attachFiles({
        data: filesObject,
        config: {
          onUploadProgress: (progressEvent) => {
            this.progress = Math.round((progressEvent.loaded * 100) / progressEvent.total);
            if (this.progress >= 100) {
              this.progress = 0;
            }
          }
        }
      });
      ev.target.value = '';

      if (res.success) {
        this.$toast(this.$t(`{count} attachment(s) were successfully uploaded`, {count: filesObject.files.length}));
        this.foldersAndFiles = res.body.map((file) => file.id);
      } else {
        this.$toast(res.body.message, 'danger');
      }
    },
  },
  mounted() {
    const parentId = this.$route.params.folderId;
    this.getFoldersByParent(parentId);
    this.getCurrentFolder(parentId);
    this.getAttachConfig();
  },
  destroyed() {
    this.destroyedCurrentFolder({});
  }
}
</script>

<style lang="scss">

</style>