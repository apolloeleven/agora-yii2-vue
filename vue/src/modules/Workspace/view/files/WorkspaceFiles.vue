<template>
  <div class="file-manager">
    <b-card no-body class="file-manager-card">
      <b-card-body>
        <div class="d-flex justify-content-between align-items-center mb-2">
          <b-breadcrumb :items="breadcrumb" class="d-none d-sm-flex mb-0"/>
          <b-form-input class="search" v-model="keyword" :placeholder="$t('Type to search files')"/>
          <div v-if="!isTimelineFolder()">
            <div class="file-manager-btn-wrapper">
              <b-button variant="success" size="sm">
                <i class="fas fa-cloud-upload-alt"/>
                {{ $t('Upload') }}
                <input class="input-file" id="file" type="file" name="file" @change="onFileChoose" multiple/>
              </b-button>
            </div>
            <div class="file-manager-btn-wrapper">
              <b-button @click="onShowModal" variant="info" size="sm">
                <i class="fas fa-plus-circle"/>
                {{ $t('Create folder') }}
              </b-button>
            </div>
            <div class="file-manager-btn-wrapper">
              <b-button :disabled="selected.length === 0" variant="danger" @click="onDeleteMultipleFiles" size="sm">
                <i class="far trash-alt"/>
                {{ $t('Delete') }}
              </b-button>
            </div>
          </div>
        </div>
        <b-table :busy="loading" small striped hover :items="filteredFoldersAndFiles" no-local-sorting :fields="fields"
                 @sort-changed="onSort" class="mb-0">
          <template v-slot:table-busy>
            <content-spinner show/>
          </template>
          <template v-slot:cell(name)="files">
            <router-link v-if="!isFile(files.item)" class="folder-name"
                         :to="{name: 'workspace.files', params: {folderId: files.item.id}}">
              <i class="fas fa-folder-open"/>
              {{ files.item.name }}
            </router-link>
            <a v-else class="file-name" @click="onFileClick(files.item)">
              <i class="far fa-file-alt"/>
              {{ files.item.name }}
            </a>
          </template>
          <template v-slot:cell(checkbox)="{item}">
            <b-form-checkbox
              v-if="!isTimelineFolder(item)" v-model="item.selected" value="1" unchecked-value="0">
            </b-form-checkbox>
          </template>
          <template v-slot:cell(size)="{item}">
            {{ item.size | prettyBytes }}
          </template>
          <template v-slot:cell(updated_at)="{item}">
            {{ item.updated_at | toDatetime }}
          </template>
          <template v-slot:cell(actions)="{item}">
            <b-dropdown variant="link" toggle-class="text-decoration-none p-0" no-caret right>
              <template v-slot:button-content>
                <i class="fas fa-ellipsis-v"/>
              </template>
              <b-dropdown-item @click="onCopyUrlClick(item)">
                <i class="far fa-copy mr-2"/>
                {{ $t('Copy') }}
              </b-dropdown-item>
              <b-dropdown-item v-if="!isFile(item) && !isTimelineFolder(item)" @click="onEditClick(item)">
                <i class="fas fa-pencil-alt mr-2"/>
                {{ $t('Edit') }}
              </b-dropdown-item>
              <b-dropdown-item v-if="!isTimelineFolder(item)" @click="onRemoveClick(item)">
                <i class="far fa-trash-alt mr-2"/>
                {{ $t('Remove') }}
              </b-dropdown-item>
              <b-dropdown-item v-if="isFile(item)" @click="onDownloadClick(item)">
                <i class="fas fa-download"/>
                {{ $t('Download') }}
              </b-dropdown-item>
            </b-dropdown>
          </template>
        </b-table>
      </b-card-body>
    </b-card>
    <FolderForm/>
    <FilePreviewModal @onDownloadClick="onDownloadClick"/>
  </div>
</template>

<script>

import {createNamespacedHelpers} from "vuex";
import FolderForm from "./FolderForm";
import ContentSpinner from "@/core/components/ContentSpinner";
import {AppSettings} from "@/shared/AppSettings";
import authService from "@/core/services/authService";
import FilePreviewModal from "./FilePreviewModal";

const {mapState: mapWorkspaceState, mapActions: mapWorkspaceActions} = createNamespacedHelpers('workspace');

export default {
  name: "WorkspaceFiles",
  components: {FilePreviewModal, ContentSpinner, FolderForm},
  data() {
    return {
      sortBy: null,
      sortDesc: null,
      keyword: '',
    }
  },
  computed: {
    ...mapWorkspaceState({
      foldersAndFiles: state => state.view.folders.folderAndFiles,
      loading: state => state.view.folders.loading,
      breadcrumb: state => state.view.folders.breadcrumb,
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
    filteredFoldersAndFiles() {
      if (!this.keyword) {
        return this.foldersAndFiles;
      }
      return this.foldersAndFiles.filter(c => c.name.toLowerCase().includes(this.keyword.toLowerCase()));
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
      'getCurrentFolder', 'deleteFolder', 'destroyedCurrentFolder', 'sortFiles', 'showPreviewModal']),
    onShowModal() {
      this.showFolderModal(null)
    },
    onEditClick(e) {
      this.showFolderModal(e)
    },
    onDeleteMultipleFiles() {
      this.showDeleteConfirmation(this.selected.map(a => a.id))
    },
    onRemoveClick(e) {
      this.showDeleteConfirmation([e.id])
    },
    onDownloadClick(e) {
      window.location.href = `${AppSettings.url()}/v1/workspaces/folder/download-file/${e.id}?access-token=${authService.getToken()}`;
    },
    onFileClick(e) {
      let files = this.foldersAndFiles.filter(f => f.is_file === 1);
      let index = files.findIndex(f => f.id === e.id);

      this.showPreviewModal({activeFile: index, files: files});
    },
    async showDeleteConfirmation(fileIds) {
      const result = await this.$confirm(
        this.$t('You are about to delete {count} item(s). Are you sure you want to continue?', {count: fileIds.length}),
        this.$t('This operation can not be undone')
      );
      if (result) {
        const res = await this.deleteFolder(fileIds);
        if (res.success) {
          this.$toast(this.$t(`File(s) were successfully deleted`));
        } else {
          this.$toast(res.body.message, 'danger');
        }
      }
    },
    onCopyUrlClick(item) {
      this.$copyText(`${window.location.origin}/workspace/${item.workspace_id}/files/${item.parent_id}`);
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
        this.$toast(this.$t(`{count} file(s) were successfully uploaded`, {count: filesObject.files.length}));
      } else {
        this.$toast(res.body.message, 'danger');
      }
    },
    onSort(e) {
      this.sortBy = e.sortBy;
      this.sortDesc = e.sortDesc;
      this.sortFiles({sortBy: this.sortBy, sortDesc: this.sortDesc});
    },
    isTimelineFolder(item = null) {
      if (item) {
        return item.timeline_post_id || item.is_timeline_folder === 1;
      } else if (this.currentFolder) {
        return this.currentFolder.timeline_post_id || this.currentFolder.is_timeline_folder === 1;
      }
      return false;
    },
    isFile(item) {
      return item.is_file === 1;
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
.file-manager-card {
  .card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
  }
}

.file-manager {
  .breadcrumb {
    background: white;
  }

  .attachment-icon {
    font-size: 18px;
  }

  .file-manager-btn-wrapper {
    position: relative;
    overflow: hidden;
    display: inline-block;
    margin-left: 2px;
  }

  .file-name {
    cursor: pointer;
  }

  .file-name:hover {
    color: #3989c6 !important;
  }

  .folder-name {
    color: #212529 !important;
  }

  .folder-name:hover {
    color: #3989c6 !important;
    text-decoration: none;
  }

  .input-file {
    font-size: 20px;
    position: absolute;
    left: 0;
    top: 0;
    opacity: 0;
  }

  td.name {
    word-break: break-all;
  }

  td.size {
    white-space: nowrap;
  }

  .actions-button-wrapper {
    button {
      margin-left: 10px;
    }
  }

  .search {
    width: 25%;
  }
}
</style>