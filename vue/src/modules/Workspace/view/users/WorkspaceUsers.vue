<template>
  <div v-if="loading">
    <content-spinner show/>
  </div>
  <div v-else class="workspace-users">
    <b-card class="partial-shadow border-0 rounded-0">
      <no-data-available v-if="users.length === 0" height="100"/>
      <table class="table" v-else>
        <tr>
          <td class="title pl-5 border-0">
            Workspace Users
          </td>
          <td class="title text-center border-0">
            #Roles
          </td>
        </tr>
        <tr v-for="(user,index) in users" v-bind:key="user.id">
          <td :class="{'border-top-0': index === 0}" style="width: 60%">
            <b-media class="pb-3 mt-3">
              <template #aside>
                <b-img width="44" class="user-image rounded-circle mx-5"
                       :src="user.image_url ? user.image_url : '/assets/img/avatar.svg'"></b-img>
              </template>
              <p class="display-name mt-2">{{ user.displayName }}</p>
            </b-media>
          </td>
          <td class="text-center align-middle role-font" :class="{'border-top-0': index === 0}">
          <span v-for="role in user.roles" :class="roleClasses[role] ? roleClasses[role] : 'default-user'">
              #{{ role }}
          </span>
          </td>
        </tr>
      </table>
    </b-card>
  </div>
</template>

<script>
import httpService from "@/core/services/httpService";
import ContentSpinner from "@/core/components/ContentSpinner";
import NoDataAvailable from "@/core/components/NoDataAvailable";

export default {
  name: "WorkspaceUsers",
  components: {ContentSpinner, NoDataAvailable},
  data() {
    return {
      users: null,
      loading: true,
      roleClasses: {
        'admin': 'role-admin',
        'workspaceAdmin': 'role-workspace-admin',
        'user': 'role-user',
      },
    }
  },
  async beforeMount() {
    let workspaceId = parseInt(this.$route.params.id);
    let {success, body} = await httpService.get('v1/workspaces/workspace/get-users?id=' + workspaceId)
    if (success) {
      this.users = body
    }
    this.loading = false;
  },
}
</script>

<style scoped>

.pagination {
  display: flex;
  justify-content: center;
}

.workspace-users {
  width: 680px;
  margin: 0 auto;
}

.border-bottom {
  border-bottom: 1px solid rgba(0, 0, 0, .05) !important;
}

.role-font {
  font-weight: bolder;
  font-size: 1rem;
  text-shadow: 1px 0 2px #d7d7e5;
}

.role-admin {
  color: #246ba8;
}

.role-workspace-admin {
  color: #dc7e6d;
}

.role-user {
  color: #58a05e;
}

.default-user {
  color: #8d8f92;
}

.roles {
  color: lightgrey;
  font-size: 1rem;
  text-shadow: 0 1px 2px lightgrey;
}

.partial-shadow {
  box-shadow: 2px 2px 6px lightgrey !important;
}

.title {
  font-size: 1.4rem;
  font-weight: bold;
  color: #bab5b5;
}

.display-name {
  font-size: 1rem;
  font-weight: 600;
  color: #636060;
}

</style>