<template>
  <div class="attachments">
    <b-card no-body class="attachment-card">
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
          <template v-slot:cell(actions)="data">
            <b-dropdown variant="link" toggle-class="text-decoration-none p-0" no-caret right>
              <template v-slot:button-content>
                <i class="fas fa-ellipsis-v"/>
              </template>
              <b-dropdown-item @click="showEditLabelModal(data.item)">
                <i class="far fa-edit"/>
                {{ $t('Edit Label') }}
              </b-dropdown-item>
              <EditButton :model="data.item" type="folder"/>
              <DeleteButton :model="data.item" type="folder"/>
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
import EditButton from "../components/EditButton";
import DeleteButton from "../components/DeleteButton";

const {mapState, mapActions} = createNamespacedHelpers('fileManager');

export default {
  name: "FileManager",
  components: {DeleteButton, EditButton, FolderForm},
  props: {
    model: Array
  },
  computed: {
    ...mapState(['foldersAndFiles']),
    fields() {
      return [
        {key: 'checkbox', label: ''},
        {key: 'name', class: 'name', sortable: true},
        {key: 'actions', class: 'text-center'}
      ]
    },
  },
  methods: {
    ...mapActions(['showFolderModal', 'getFoldersByWorkspace']),
    showEditLabelModal(file) {
      this.showEditLabelDialog(file)
    },
    showModal() {
      this.showFolderModal(null)
    },
    async onFileChoose(ev) {
      console.log(ev)
    }
  },
  mounted() {
    const workspaceId = this.$route.params.id;

    this.getFoldersByWorkspace(workspaceId);
  },
}
</script>

<style lang="scss">
.attachments {
  .file-manager-btn-wrapper {
    position: relative;
    overflow: hidden;
    display: inline-block;
    margin: 2px;
  }
}
</style>