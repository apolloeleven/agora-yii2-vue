<template>
  <div v-if="loading">
    <content-spinner show/>
  </div>
  <div v-else class="workspace-users">
    <user-table :data="users" :loading="loading" :visible-fields="visibleFields" :workspace-id="workspaceId"/>
  </div>
</template>

<script>
import ContentSpinner from "@/core/components/ContentSpinner";
import {createNamespacedHelpers} from "vuex";
import UserTable from "../../../setup/employees/UserTable";

const {mapState, mapActions} = createNamespacedHelpers('employee');

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
      workspaceId: null,
      visibleFields: ['id', 'avatar', 'fullName', 'email', 'verified', 'role']
    }
  },
  computed: {
    ...mapState({
      loading: state => state.loading,
      users: state => state.data.rows,
    })
  },
  methods: {
    ...mapActions(['getData']),
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