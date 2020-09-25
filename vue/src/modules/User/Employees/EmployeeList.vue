<template>
  <div id="user" class="d-flex flex-column">
    <content-spinner :show="loading" :text="$t('Loading...')" class="h-100"/>
    <div v-if="!loading" id="employees" class="p-3">
      <b-card>
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
            <ul v-if="data.item.userDepartments.length > 0" style="width: 100%">
              <li v-for="dep in data.item.userDepartments">
                {{ dep.department.name }}
              </li>
            </ul>
          </template>
          <template v-slot:cell(country)="data">
            <ul v-if="data.item.userDepartments.length > 0" style="width: 100%">
              <li v-for="dep in data.item.userDepartments">
                {{ dep.department.country.name }}
              </li>
            </ul>
          </template>
          <template v-slot:cell(position)="data">
            <ul v-if="data.item.userDepartments.length > 0" style="width: 100%">
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
              <b-dropdown-item @click="editUser(data.item)" variant="text-dark"><i class="icon-eye"></i>
                Edit
              </b-dropdown-item>
            </b-dropdown>
          </template>
        </b-table>
      </b-card>
    </div>
  </div>

</template>

<script>
import {createNamespacedHelpers} from 'vuex';
import ContentSpinner from "../../../core/components/ContentSpinner";

const {mapState, mapActions} = createNamespacedHelpers('user/employees');

export default {
  name: "EmployeeList",
  components: {ContentSpinner},
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
    ...mapActions(['getData', 'showEmployeeModal', 'getModalDropdownData']),
    editUser(employee) {
      this.showEmployeeModal(employee);
    }
  },
  mounted() {
    this.getModalDropdownData();
    this.getData();
  }
}
</script>

<style scoped>

</style>