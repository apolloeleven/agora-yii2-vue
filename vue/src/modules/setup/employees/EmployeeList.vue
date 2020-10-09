<template>
  <div id="user" class="page page-with-table">
    <page-header :title="$t('Users')"/>
    <div class="content-wrapper p-3">
      <content-spinner :show="loading" :text="$t('Loading...')" class="h-100"/>
      <div v-if="!loading">
        <b-card no-body>
          <b-table class="mb-0" responsive small striped hover id="users-table" :items="data.rows" :fields="fields">
            <template v-slot:table-busy>
              <div class="text-center text-danger my-2">
                <b-spinner class="align-middle"></b-spinner>
                <strong>{{ $t('Loading...') }}</strong>
              </div>
            </template>
            <template v-slot:cell(fullName)="data">
              <div style="width: 100%">
                {{ data.item.displayName }}
              </div>
            </template>
            <template v-slot:cell(departments)="data">
              <ul v-if="data.item.userDepartments.length > 0"
                  style="width: 100%; list-style-type: none;padding-inline-start: 0">
                <li v-for="dep in data.item.userDepartments">
                  {{ dep.department.name }}
                </li>
              </ul>
            </template>
            <template v-slot:cell(country)="data">
              <ul v-if="data.item.userDepartments.length > 0"
                  style="width: 100%; list-style-type: none; padding-inline-start: 0">
                <li v-for="dep in data.item.userDepartments">
                  {{ dep.department.country.name }}
                </li>
              </ul>
            </template>
            <template v-slot:cell(position)="data">
              <ul v-if="data.item.userDepartments.length > 0"
                  style="width: 100%; list-style-type: none; padding-inline-start: 0">
                <li v-for="dep in data.item.userDepartments" :key="dep.id">
                  {{ dep.position }}
                </li>
              </ul>
            </template>
            <template v-slot:cell(actions)="data">
              <b-dropdown variant="transparent text-dark p-0" right no-caret>
                <template slot="button-content">
                  <i class="fas fa-ellipsis-v"></i>
                </template>
                <b-dropdown-item @click="editUser(data.item)">
                  <i class="fas fa-pencil-alt"></i>
                  {{ $t('Edit') }}
                </b-dropdown-item>
                <b-dropdown-item @click="deleteUser(data.item)">
                  <i class="far fa-trash-alt"></i>
                  {{ $t('Delete') }}
                </b-dropdown-item>
              </b-dropdown>
            </template>
          </b-table>
        </b-card>
      </div>
    </div>
  </div>

</template>

<script>
import {createNamespacedHelpers} from 'vuex';
import ContentSpinner from "../../../core/components/ContentSpinner";
import PageHeader from "@/core/components/PageHeader";

const {mapState, mapActions} = createNamespacedHelpers('employee');

export default {
  name: "EmployeeList",
  components: {PageHeader, ContentSpinner},
  data() {
    return {}
  },
  computed: {
    ...mapState(['loading', 'data']),
    fields() {
      return [
        {key: 'id', label: this.$t('ID')},
        {key: 'fullName', label: this.$t('Full Name')},
        {key: 'email', label: this.$t('Email')},
        {key: 'departments', label: this.$t('Departments')},
        {key: 'country', label: this.$t('Country')},
        {key: 'position', label: this.$t('Position')},
        {key: 'actions', label: this.$t('Actions')},
      ]
    }
  },
  methods: {
    ...mapActions(['getData', 'showEmployeeModal', 'getModalDropdownData', 'deleteEmployee']),
    editUser(employee) {
      this.showEmployeeModal(employee);
    },
    async deleteUser(employee) {
      const result = await this.$confirm(this.$t('Are you sure you want to delete this user?'),
        this.$t('This operation can not be undone'))
      if (result) {
        const {success, body} = await this.deleteEmployee(employee);
        if (success) {
          this.$toast(this.$t(`User was successfully deleted`));
        } else {
          this.$toast(body.message, 'danger');
        }
      }
    },
  },
  mounted() {
    this.getModalDropdownData();
    this.getData();
  }
}
</script>

<style scoped>

</style>