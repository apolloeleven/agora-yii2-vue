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
            <template v-slot:cell(avatar)="data">
              <b-img :src="data.item.image_url || '/assets/img/avatar.svg'" rounded="circle" width="32" height="32"/>
            </template>
            <template v-slot:cell(fullName)="data">
              <div style="width: 100%">
                {{ data.item.displayName }}
              </div>
            </template>
            <template v-slot:cell(verified)="data">
              <b-form-checkbox v-model="data.item.status" name="check-button" switch size="lg"
                               @change="verifiedUser(data.item)"/>
            </template>
            <template v-slot:cell(actions)="data">
              <b-tooltip target="edit-tooltip" placement="bottom" boundary-padding="35" triggers="hover" variant="info" noninteractive="true">
                Edit
              </b-tooltip>
              <b-tooltip target="delete-tooltip" placement="bottom" boundary-padding="35" triggers="hover" variant="danger" noninteractive="true">
                Delete
              </b-tooltip>
              <i id="edit-tooltip" class="fas fa-pencil-alt mr-3 text-primary hover-pointer" @click="editUser(data.item)"/>
              <i id="delete-tooltip" class="far fa-trash-alt mr-3 text-danger hover-pointer" @click="deleteUser(data.item)"/>
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
        {key: 'avatar', label: this.$t('Image')},
        {key: 'fullName', label: this.$t('Full Name')},
        {key: 'email', label: this.$t('Email')},
        {key: 'verified', label: this.$t('Verified')},
        {key: 'actions', label: this.$t('Actions')},
      ]
    }
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
    this.getModalDropdownData();
    this.getData();
  }
}
</script>

<style scoped>

</style>