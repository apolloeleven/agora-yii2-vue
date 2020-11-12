<template>
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
        <b-button :disabled="selected.length === 0" variant="outline-danger" @click="onDeleteMultipleFiles">
          <i class="far trash-alt"/>
          {{ $t('Delete') }}
        </b-button>
      </div>
      <b-table small striped hover :items="model" no-local-sorting :fields="fields">
        <template v-slot:table-busy>
          <div class="text-center text-danger my-2">
            <b-spinner class="align-middle"/>
            <strong>{{ $t('Loading...') }}</strong>
          </div>
        </template>
        <template v-slot:cell(name)="files">
          <router-link v-if="files.item.is_file === 0" class="folder-name"
                       :to="{name: 'workspace.folder', params: {folderId: files.item.id}}">
            {{ files.item.name }}
          </router-link>
          <!--TODO attachment preview-->
          <a v-else class="file-name">
            {{ files.item.name }}
          </a>
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
            <b-dropdown-item v-if="data.item.is_file === 0" @click="editClicked(data.item)">
              <i class="fas fa-pencil-alt mr-2"></i>
              {{ $t('Edit') }}
            </b-dropdown-item>
            <b-dropdown-item @click="removeClicked(data.item)">
              <i class="far fa-trash-alt mr-2"></i>
              {{ $t('Remove') }}
            </b-dropdown-item>
          </b-dropdown>
        </template>
      </b-table>
    </b-card-body>
  </b-card>
</template>

<script>
import DropdownButtons from "../../../../core/components/DropdownButtons";

export default {
  name: "FolderItems",
  components: {DropdownButtons},
  props: {
    model: Array,
    fields: Array,
    selected: Array
  },
  methods: {
    onFileChoose(e) {
      this.$emit('onFileChoose', e)
    },
    showModal(e) {
      this.$emit('showModal', e)
    },
    removeClicked(e) {
      this.$emit('removeClicked', e)
    },
    editClicked(e) {
      this.$emit('editClicked', e)
    },
    onDeleteMultipleFiles(e) {
      this.$emit('onDeleteMultipleFiles', e)
    },
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
}
</style>