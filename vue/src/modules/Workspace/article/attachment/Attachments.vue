<template>
  <div class="attachments">
    <b-card no-body class="attachment-card">
      <template v-slot:header>
        <h5 class="mb-0">{{ $t('Attachments') }}</h5>
        <b-button class="p-0" variant="link" size="lg" id="attachment-technical-notes">
          <i class="fas fa-question-circle"/>
        </b-button>
      </template>
      <b-popover target="attachment-technical-notes" triggers="hover" placement="bottom">
        <template v-slot:title>{{ $t('Technical note') }}</template>
        <ul class="technical-notes">
          <li v-for="(value ,key) in attachConfig" v-bind:key="key">
            <b>{{ $t(value.title) }}</b>: {{ value.size }}
          </li>
        </ul>
      </b-popover>
      <b-card-body>
        <div class="d-flex justify-content-between align-items-center mb-2">
          <div class="upload-btn-wrapper">
            <b-button variant="success">
              <i class="fas fa-cloud-upload-alt"/>
              {{ $t('Upload') }}
              <input class="input-file" id="file" type="file" name="file" @change="onFileChoose" multiple/>
            </b-button>
          </div>
          <span v-if="progress > 0">{{ $t('Uploading...') }}</span>
          <div class="action-button-wrapper">
            <b-button id="columnOptions">Columns</b-button>
            <b-popover target="columnOptions" triggers="hover" placement="bottom">
              <template v-slot:title>{{ $t('Show/Hide columns') }}</template>
              <div>
                <b-form-checkbox v-for="column in visibleColumns" v-model="column.visible">
                  {{ column.label }}
                </b-form-checkbox>
              </div>
            </b-popover>
            <AttachmentShareButton :disabled="selected.length === 0" :model="articleFiles" :fileIds="selectedIds"/>
            <AttachmentDeleteButton
              tag="button" :disabled="selected.length === 0" :model="currentArticle" :fileIds="selectedIds">
            </AttachmentDeleteButton>
          </div>
        </div>
        <div class="mb-1" v-if="progress !== 0">
          <b-progress height="7px" :value="progress" variant="primary"/>
        </div>
        <b-table small striped hover :items="articleFiles" no-local-sorting :fields="fields" @sort-changed="onSort">
          <template v-slot:table-busy>
            <div class="text-center text-danger my-2">
              <b-spinner class="align-middle"/>
              <strong>{{ $t('Loading...') }}</strong>
            </div>
          </template>
          <template v-slot:cell(checkbox)="{item}">
            <b-form-checkbox v-model="item.selected" value="1" unchecked-value="0"/>
          </template>
          <template v-slot:cell(name)="articleFiles">
            <a class="attachment-name" @click="previewAttachment(articleFiles.index)">
              {{ articleFiles.item.label || articleFiles.item.name }}
            </a>
          </template>
          <template v-slot:cell(size)="{item}">
            {{ item.size | prettyBytes }}
          </template>
          <template v-slot:cell(updated_at)="{item}">
            {{ item.updated_at | toDatetime }}
          </template>
          <template v-slot:cell(updated_by)="{item}">
            {{ item.updatedBy.displayName }}
          </template>
          <template v-slot:cell(actions)="data">
            <b-dropdown variant="link" toggle-class="text-decoration-none p-0" no-caret right>
              <template v-slot:button-content>
                <i class="fas fa-ellipsis-v"/>
              </template>
              <b-dropdown-item @click="showEditLabelModal(data.item)">
                <i class="far fa-edit"/>
                {{ $t('Edit Label') }}
              </b-dropdown-item>
              <AttachmentDownloadButton tag="dropdown" :file="data.item"/>
              <AttachmentShareButton tag="dropdown" :fileIds="[data.item.id]" :model="articleFiles"/>
              <AttachmentDeleteButton tag="dropdown" :fileIds="[data.item.id]" :model="currentArticle"/>
            </b-dropdown>
          </template>
        </b-table>
      </b-card-body>
    </b-card>
    <AttachmentPreview :model="currentArticle"/>
    <AttachmentLabel/>
  </div>
</template>

<script>
import {createNamespacedHelpers} from "vuex";
import AttachmentLabel from "./AttachmentLabel";
import AttachmentDeleteButton from "./AttachmentDeleteButton";
import AttachmentDownloadButton from "./AttachmentDownloadButton";
import AttachmentPreview from "./AttachmentPreview";
import AttachmentShareButton from "./AttachmentShareButton";

const {mapState, mapActions} = createNamespacedHelpers('article');
export default {
  name: "Attachments",
  components: {
    AttachmentShareButton,
    AttachmentPreview, AttachmentDownloadButton, AttachmentDeleteButton, AttachmentLabel
  },
  data() {
    return {
      articleFileIds: [],
      uploadedAttachmentIds: [],
      progress: 0,
      sortBy: null,
      sortDesc: null
    }
  },
  computed: {
    ...mapState(['visibleColumns', 'articleFiles', 'attachConfig', 'currentArticle']),
    fields() {
      return [
        {key: 'checkbox', label: ''},
        {key: 'name', class: 'name', sortable: true},
        ...this.visibleColumns.filter(c => c.visible),
        {key: 'actions', class: 'text-center'}
      ]
    },
    selected() {
      return this.articleFiles.filter(a => a.selected === '1')
    },
    selectedIds() {
      return this.selected.map(a => a.id)
    },
  },
  watch: {
    progress() {
      if (this.progress >= 100) {
        setTimeout(() => {
          this.progress = 0;
        }, 2000);
      }
    }
  },
  methods: {
    ...mapActions(['getAttachConfig', 'attachFiles', 'showEditLabelDialog', 'showPreviewModal', 'sortAttachment']),
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
          const result = await this.$confirm(this.$t(`${checkFiles.join(',')} file already exist. Are you sure that you want to overwrite them?`),
            this.$t('Filename conflicts'))
          if (result) {
            this.filesAttach(ev, this.currentArticle.id, filesArray)
          }
        } else {
          this.filesAttach(ev, this.currentArticle.id, filesArray);
        }
      }
    },
    async filesAttach(ev, currentArticleId, filesArray) {
      const filesObject = {
        article_id: currentArticleId,
        files: filesArray,
      };
      const res = await this.attachFiles({
        data: filesObject,
        config: {
          onUploadProgress: (progressEvent) => {
            this.progress = Math.round((progressEvent.loaded * 100) / progressEvent.total);
          }
        }
      });
      ev.target.value = '';

      if (res.success) {
        this.$toast(this.$t(`{count} attachment(s) were successfully uploaded`, {count: filesObject.files.length}));
        this.articleFileIds = res.body.map((file) => file.id);
      } else {
        this.$toast(res.body.message, 'danger');
      }
    },
    checkFileNames(fileNames) {
      return this.articleFiles.filter(f => fileNames.includes(f.name)).map(f => f.name);
    },
    showEditLabelModal(file) {
      this.showEditLabelDialog(file)
    },
    previewAttachment(index) {
      this.showPreviewModal({activeFile: index, files: this.articleFiles});
    },
    onSort(column) {
      this.sortBy = column.sortBy;
      this.sortDesc = column.sortDesc;
      this.sortAttachment({sortBy: this.sortBy, sortDesc: this.sortDesc});
    },
  },
  mounted() {
    this.getAttachConfig();
  },
}
</script>

<style lang="scss">
.attachment-card {
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

.attachments {
  .attachment-icon {
    font-size: 18px;
  }

  .upload-btn-wrapper {
    position: relative;
    overflow: hidden;
    display: inline-block;
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