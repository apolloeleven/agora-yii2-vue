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
              <b-button size="sm" variant="outline-danger" @click="onDeleteClick(data.item)" data-container="body">
                <i class="far fa-trash-alt"></i>
                {{$t('Remove')}}
              </b-button>
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

const {mapState, mapActions} = createNamespacedHelpers('employee');
const {mapState: mapStateUser} = createNamespacedHelpers('user');

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
    }),
    ...mapStateUser(['currentUser'])
  },
  methods: {
    ...mapActions(['getData', 'removeFromWorkspace']),
    async onDeleteClick(user) {
      if(user.id == this.currentUser.data.id) {
        this.$alert(this.$t(`You can't delete yourself from the workspace`));
        return;
      }
      const confirm = await this.$confirm(this.$t('Are you sure you want to remove the user "{user}" from the workspace?', {user: user.display_name}))
      if (confirm) {
        const response = await this.removeFromWorkspace({userId: user.id, workspaceId: this.workspaceId});
        if (response.success) {
          this.$successToast(this.$t(`User "{user}" has been removed from the workspace`))
        } else {
          this.$alert(response.body);
        }
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