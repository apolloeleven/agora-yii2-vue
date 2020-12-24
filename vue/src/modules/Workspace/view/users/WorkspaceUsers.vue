<template>
  <div v-if="loading">
    <content-spinner show/>
  </div>
  <div v-else class="workspace-users">
    <user-table :data="users" :loading="loading" :visible-fields="visibleFields"/>
  </div>
</template>

<script>
import ContentSpinner from "@/core/components/ContentSpinner";
import {createNamespacedHelpers} from "vuex";
import UserTable from "../../../setup/employees/UserTable";

const {mapState, mapActions} = createNamespacedHelpers('workspace');

export default {
  name: "WorkspaceUsers",
  components: {UserTable, ContentSpinner},
  data() {
    return {
      roleColor: {
        'admin': '#246ba8',
        'workspaceAdmin': '#dc7e6d',
        'user': '#58a05e',
        'default': '#8d8f92'
      },
      visibleFields: ['id', 'avatar', 'fullName', 'email', 'verified', 'role']
    }
  },
  computed: {
    ...mapState({
      loading: state => state.view.users.loading,
      users: state => state.view.users.data,
    })
  },
  methods: {
    ...mapActions(['getWorkspaceUsers']),
  },
  async beforeMount() {
    let workspaceId = parseInt(this.$route.params.id);
    await this.getWorkspaceUsers(workspaceId);
  },
}
</script>

<style scoped>

.workspace-users {
  width: 680px;
  margin: 0 auto;
}


</style>