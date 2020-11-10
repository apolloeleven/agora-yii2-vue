<template>
  <div class="file-manager">
    <b-card no-body class="file-manager-card">
      <b-card-body>
        <div class="d-flex justify-content-between align-items-center mb-2">
          <div>
            <div class="file-manager-btn-wrapper">
              <b-button variant="success">
                <i class="fas fa-cloud-upload-alt"/>
                {{ $t('Upload') }}
                <input class="input-file" id="file" type="file" name="file" @change="onFileChoose" multiple/>
              </b-button>
            </div>
            <div class="file-manager-btn-wrapper">
              <b-button @click="showModal" variant="info">
                <i class="fas fa-plus-circle"/>
                {{ $t('Create folder') }}
              </b-button>
            </div>
          </div>
        </div>
        <b-table small striped hover :items="foldersAndFiles" no-local-sorting :fields="fields">
          <template v-slot:table-busy>
            <div class="text-center text-danger my-2">
              <b-spinner class="align-middle"/>
              <strong>{{ $t('Loading...') }}</strong>
            </div>
          </template>
          <template v-slot:cell(checkbox)="{item}">
            <b-form-checkbox v-model="item.selected" value="1" unchecked-value="0"/>
          </template>
          <template v-slot:cell(size)="{item}">
            {{ item.size | prettyBytes }}
          </template>
          <template v-slot:cell(updated_at)="{item}">
            {{ item.updated_at | toDatetime }}
          </template>
          <template v-slot:cell(actions)="data">
            <b-dropdown variant="link" toggle-class="text-decoration-none p-0" no-caret right>
              <template v-slot:button-content>
                <i class="fas fa-ellipsis-v"/>
              </template>
            </b-dropdown>
          </template>
        </b-table>
      </b-card-body>
    </b-card>
    <FolderForm/>
  </div>
</template>

<script>

import {createNamespacedHelpers} from "vuex";
import FolderForm from "./FolderForm";
import DropdownButtons from "../../../../core/components/DropdownButtons";

const {mapState: mapWorkspaceState, mapActions: mapWorkspaceActions} = createNamespacedHelpers('workspace');

export default {
  name: "WorkspaceFiles",
  components: {DropdownButtons, FolderForm},
  props: {
    model: Array
  },
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
  },
  methods: {
    ...mapWorkspaceActions(['showFolderModal', 'getFoldersByWorkspace', 'attachFiles', 'getAttachConfig']),
    showModal() {
      this.showFolderModal(null)
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
            this.filesAttach(ev, this.$route.params.id, filesArray)
          }
        } else {
          this.filesAttach(ev, this.$route.params.id, filesArray);
        }
      }
    },
    async filesAttach(ev, workspaceId, filesArray) {
      const filesObject = {
        workspace_id: workspaceId,
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
    const workspaceId = this.$route.params.id;

    this.getFoldersByWorkspace(workspaceId);
    this.getAttachConfig();
  },
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

.technical-notes {
  list-style: none;
  padding-left: 10px;
  font-size: 16px;
}

.technical-notes-headline {
  margin: 0;
  padding-left: 5px;
  font-style: italic;
}

.file-manager {
  .attachment-icon {
    font-size: 18px;
  }

  .file-manager-btn-wrapper {
    position: relative;
    overflow: hidden;
    display: inline-block;
    margin: 2px;
  }

  .attachment-name {
    cursor: pointer;
  }

  .attachment-name:hover {
    color: #3989c6 !important;
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

  .action-button-wrapper {
    button {
      margin-left: 10px;
    }
  }
}
</style>