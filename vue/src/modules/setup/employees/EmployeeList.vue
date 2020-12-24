<template>
  <div id="user" class="page page-with-table">
    <page-header :title="$t('Users')"/>
    <user-table :data="data.rows"
                :loading="loading"
                :visible-fields="visibleFields"
                @edit-click="editUser"
                @delete-click="deleteUser"
                @status-change="verifiedUser"/>
  </div>

</template>

<script>
import {createNamespacedHelpers} from 'vuex';
import PageHeader from "@/core/components/PageHeader";
import UserTable from "./UserTable";

const {mapState, mapActions} = createNamespacedHelpers('employee');

export default {
  name: "EmployeeList",
  components: {UserTable, PageHeader},
  data() {
    return {
      visibleFields: ['id', 'avatar', 'fullName', 'email', 'verified', 'actions']
    }
  },
  computed: {
    ...mapState(['loading', 'data']),
  },
  methods: {
    ...mapActions(['getData', 'showEmployeeModal', 'getModalDropdownData', 'deleteEmployee', 'updateUserStatus']),
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
    async verifiedUser(employee) {
      const {success, body} = await this.updateUserStatus(employee);
      if (success) {
        this.$toast(this.$t(`Status was successfully changed`));
      } else {
        this.$toast(body.message, 'danger');
      }
    },
  },
  mounted() {
    this.getData();
  }
}
</script>

<style scoped>

</style>