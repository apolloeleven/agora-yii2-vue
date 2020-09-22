<template>
  <div class="customer-list page page-with-table">
    <page-header :title="$t('Departments')">
      <b-button variant="primary" @click="showDepartmentModal">
        <i class="fas fa-plus-circle"></i>
        {{$t('Add new department')}}
      </b-button>
    </page-header>
    <div class="content-wrapper p-3">
      <content-spinner :show="departments.loading" :text="$t('Please wait...')" class="h-100"/>
      <b-card no-body>
        <department-list-group v-if="!departments.loading && departments.loaded"
                               :edit-handler="editDepartment" :delete-handler="onDeleteDepartment"
                               :departments="departmentsTree"/>
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
  import DepartmentListGroup from "@/modules/setup/departments/DepartmentListGroup";

  const {mapActions, mapState, mapGetters} = createNamespacedHelpers('setup');
  export default {
    name: "DepartmentList",
    components: {DepartmentListGroup, DepartmentModal, PageHeader, ContentSpinner},
    data() {
      return {}
    },
    computed: {
      ...mapState(['departments']),
      ...mapGetters(['departmentsTree'])
    },
    methods: {
      ...mapActions(['getDepartments', 'getCountries', 'showDepartmentModal', 'deleteDepartment']),
      editDepartment(department) {
        this.showDepartmentModal(department)
      },
      async onDeleteDepartment(department) {
        const result = await this.$confirm(i18n.t(`Are you sure you want to delete that department?`))
        if (result) {
          const {success, body} = await this.deleteDepartment(department.id)
          if (!success) {
            this.$alert(i18n.t(body.message || i18n.t('There was some problem. Please try again in several minutes...')))
          }
        }
      }
    },
    mounted() {
      this.getDepartments();
      this.getCountries();
    }
  }
</script>

<style scoped>

</style>
