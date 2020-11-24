<template>
  <div v-if="loading">
    <content-spinner show/>
  </div>
  <div v-else class="workspace-users">
    <b-card class="rounded-0 border-0">
      <no-data-available v-if="!users" height="100"/>
      <table class="table table-striped" v-else>
        <tbody class="text-center">
        <tr v-for="user in users" :key="user.id">
          <td class="align-middle py-1" style="width: 20%">
            <b-img width="34" class="user-image rounded-circle mx-5"
                   :src="user.image_url ? user.image_url : '/assets/img/avatar.svg'"></b-img>
          </td>
          <td class="align-middle py-1 text-dark font-weight-bold">
            {{ user.display_name }}
          </td>
          <td class="align-middle py-2" style="width: 30%">
            <span v-for="(role,index) in user.roles" :style="{'color': roleColor[role] || roleColor['default']}"
                  class='px-1' :key="`${role}-${index}`">{{ role }}</span></td>
        </tr>
        </tbody>
      </table>
    </b-card>
  </div>
</template>

<script>
import ContentSpinner from "@/core/components/ContentSpinner";
import NoDataAvailable from "@/core/components/NoDataAvailable";
import {createNamespacedHelpers} from "vuex";

const {mapState, mapActions} = createNamespacedHelpers('workspace');

export default {
  name: "WorkspaceUsers",
  components: {ContentSpinner, NoDataAvailable},
  computed: {
    ...mapState({
      loading: state => state.view.users.loading,
      users: state => state.view.users.data,
    })
  },
  data() {
    return {
      roleColor: {
        'admin': '#246ba8',
        'workspaceAdmin': '#dc7e6d',
        'user': '#58a05e',
        'default': '#8d8f92'
      },
    }
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