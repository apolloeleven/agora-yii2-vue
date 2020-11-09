<template>
  <div class="attachments">
    <b-card no-body class="attachment-card">
      <template v-slot:header>
        <h5 class="mb-0">{{ $t('File Manager') }}</h5>
        <b-button class="p-0" variant="link" size="lg" id="attachment-technical-notes">
          <i class="fas fa-question-circle"/>
        </b-button>
      </template>
      <b-card-body>
        <div class="d-flex justify-content-between align-items-center mb-2">
          <div class="upload-btn-wrapper">
            <b-button variant="success">
              <i class="fas fa-cloud-upload-alt"/>
              {{ $t('Upload') }}
              <input class="input-file" id="file" type="file" name="file" multiple/>
            </b-button>
            &nbsp;<b-button @click="showModal" variant="info">
              <i class="fas fa-plus-circle"/>
              {{ $t('Create folder') }}
            </b-button>
          </div>
        </div>
        <b-table small striped hover :items="articleFiles" no-local-sorting :fields="fields">
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
            </b-dropdown>
          </template>
        </b-table>
      </b-card-body>
    </b-card>
  </div>
</template>

<script>

import {createNamespacedHelpers} from "vuex";

const {mapState, mapActions} = createNamespacedHelpers('article');

export default {
  name: "FileManager",
  props: {
    model: Array
  },
  computed: {
    ...mapState(['articleFiles']),
    fields() {
      return [
        {key: 'checkbox', label: ''},
        {key: 'name', class: 'name', sortable: true},
        {key: 'actions', class: 'text-center'}
      ]
    },
  },
  methods: {
    showEditLabelModal(file) {
      this.showEditLabelDialog(file)
    },
    showModal() {
      this.showArticleModal({isArticle: false, article: null})
    },
  },
}
</script>

<style scoped>

</style>