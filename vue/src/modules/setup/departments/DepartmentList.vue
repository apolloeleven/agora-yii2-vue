<template>
  <div class="customer-list page page-with-table">
    <page-header :title="$t('Departments')">
      <b-button variant="primary" @click="showDepartmentModal">
        <i class="fas fa-plus-circle"></i>
        {{ $t('Add new department') }}
      </b-button>
    </page-header>
    <div class="content-wrapper p-3">
      <content-spinner :show="countries.loading" :text="$t('Please wait...')" class="h-100"/>
      <b-card v-if="!countries.loading && countries.loaded" no-body>
        <b-table striped hover :items="countries.data" :fields="fields" class="mb-0">
          <template v-slot:cell(parent_id)="data">
            {{ data.item.parent || data.item.parent.name }}
          </template>
          <template v-slot:cell(country_id)="data">
            {{ data.item.country.name }}
          </template>
          <template v-slot:cell(created_at)="data">
            {{ data.item.created_at | toDatetime }}
          </template>
          <template v-slot:cell(createdBy)="data">
            {{ data.item.createdBy.email }}
          </template>
          <template v-slot:cell(actions)="data">
            <b-button variant="outline-primary" size="sm" class="mr-2" v-b-tooltip.hover :title="$t('Edit Department')"
                      @click="editDepartment(data.item)">
              <i class="fas fa-edit"></i>
            </b-button>
            <b-button variant="outline-danger" size="sm" v-b-tooltip.hover :title="$t('Delete Department')"
                      @click="onDeleteDepartment(data.item)">
              <i class="fas fa-trash-alt"></i>
            </b-button>
          </template>
        </b-table>
      </b-card>
    </div>
    <department-modal/>
  </div>
</template>

<script>
import {createNamespacedHelpers} from 'vuex';
import ContentSpinner from "@/core/components/ContentSpinner";
import PageHeader from "@/core/components/PageHeader";
import DepartmentModal from "@/modules/setup/departments/DepartmentModal";
import i18n from "@/shared/i18n";

const {mapActions, mapState} = createNamespacedHelpers('setup');
export default {
  name: "DepartmentList",
  components: {DepartmentModal, PageHeader, ContentSpinner},
  data() {
    return {
      fields: [
        'name',
        {
          key: 'parent_id',
          label: i18n.t('Parent Department')
        },
        {
          key: 'country_id',
          label: i18n.t('Country')
        },
        'created_at',
        'createdBy',
        'actions'
      ],
    }
  },
  computed: {
    ...mapState(['countries'])
  },
  methods: {
    ...mapActions(['getDepartments', 'showDepartmentModal', 'deleteDepartment']),
    editDepartment(department) {
      this.showDepartmentModal(department)
    },
    async onDeleteDepartment(department) {
      const result = await this.$confirm(i18n.t(`Are you sure you want to delete that department?`))
      if (result) {
        this.deleteDepartment(department.id)
      }
    }
  },
  mounted() {
    this.getDepartments();
  }
}
</script>

<style scoped>

</style>