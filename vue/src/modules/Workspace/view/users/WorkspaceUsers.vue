<template>
  <div v-if="loading">
    <content-spinner show/>
  </div>
  <div v-else class="workspace-users">
    <div id="user-table" class="content-wrapper">
      <content-spinner :show="loading" :text="$t('Loading...')" class="h-100"/>
      <div v-if="!loading">
        <b-card no-body>
          <b-table class="mb-0" responsive small hover id="users-table" :items="users" :fields="fields">
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
                {{ data.item.display_name }}
              </div>
            </template>
            <template v-slot:cell(role)="data">
              <b-form-select style="width: auto;" v-model="data.item.role" @change="onRoleChange(data.item)"
                             :options="dropdownData.userRoles"></b-form-select>
            </template>
            <template v-slot:cell(verified)="data">
              <b-form-checkbox v-model="data.item.status" name="check-button" switch size="lg"
                               @change="$emit('status-change', data.item)"/>
            </template>
            <template v-slot:cell(actions)="data">
              <span v-b-tooltip.hover data-placement="top" :title="$t('Remove from workspace')">
                <i id="delete-tooltip"
                   class="far fa-trash-alt mr-3 text-danger hover-pointer"
                   @click="onDeleteClick(data.item)"/>
                </span>
            </template>
          </b-table>
        </b-card>
      </div>
    </div>

  </div>
</template>

<script>
import ContentSpinner from "@/core/components/ContentSpinner";
import {createNamespacedHelpers} from "vuex";
import UserTable from "../../../setup/employees/UserTable";

const {mapState, mapActions} = createNamespacedHelpers('employee');

export default {
  name: "WorkspaceUsers",
  components: {ContentSpinner},
  data() {
    return {
      roleColor: {
        'admin': '#246ba8',
        'workspaceAdmin': '#dc7e6d',
        'user': '#58a05e',
        'default': '#8d8f92'
      },
      workspaceId: null,
      fields: [
        {key: 'id', label: this.$t('ID')},
        {key: 'avatar', label: this.$t('Image')},
        {key: 'fullName', label: this.$t('Full Name')},
        {key: 'email', label: this.$t('Email')},
        {key: 'role', label: this.$t('Role')},
        {key: 'verified', label: this.$t('Verified')},
        {key: 'actions', label: this.$t('Actions')},
      ]
    }
  },
  computed: {
    ...mapState({
      loading: state => state.loading,
      users: state => state.data.rows,
      dropdownData: state => state.modalDropdownData
    })
  },
  methods: {
    ...mapActions(['getData', 'removeFromWorkspace']),
    async onDeleteClick(user) {
      const result = await this.$confirm(this.$t('Are you sure you want to remove the user "{user}" from the workspace?', {user: user.display_name}))
      if (result) {
        this.removeFromWorkspace({userId: user.id, workspaceId: this.workspaceId});
      }
    }
  },
  async beforeMount() {
    this.workspaceId = this.$route.params.id ? parseInt(this.$route.params.id) : this.workspaceId;
    await this.getData({workspaceId: this.workspaceId});

  },
}
</script>

<style scoped>
.workspace-users {
  width: 680px;
  margin: 0 auto;
}
</style>