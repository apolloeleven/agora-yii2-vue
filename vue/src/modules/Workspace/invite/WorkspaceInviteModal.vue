<template>
  <ValidationObserver ref="form" v-slot="{ handleSubmit, invalid ,reset}">
    <b-modal modal-class="workspace-invite" id="invite-modal" :visible="showModal" ref="inviteModal"
             :title='$t(`Invite members`)' @hidden="onHideModal" @ok.prevent="handleSubmit(onSubmit)"
             :ok-title="$t('Submit')" :ok-disabled="!isSelectedUsers && !isSelectedCheckbox" ok-only scrollable>
      <b-form @submit.prevent="handleSubmit(onSubmit)" novalidate>
        <div class="p-2">
          <b-form-input
            :disabled="isSelectedCheckbox" v-model="usersFilterKeyword" :placeholder="$t('Type to search user')">
          </b-form-input>
        </div>
        <b-card-body class="pb-0" v-if="isSelectedUsers">
          <b-row>
            <b-col v-for="user in selectedUsers" class="col-2 pl-0 pr-0 text-center">
              <span class="close-button hover-pointer" @click="onRemoveClick(user)">&times;</span>
              <b-img rounded="circle" :src="user.image_url || '/assets/img/avatar.svg'" height="42" width="42"/>
              <h6 class="mb-0 user-name">{{ user.first_name }}</h6>
              <h6 class="mt-0 user-name">{{ user.last_name }}</h6>
            </b-col>
          </b-row>
        </b-card-body>
        <b-card-body class="pl-0" v-if="usersFilterKeyword && !isSelectedCheckbox && isFilteredUsers">
          <b-media @click="onUserClick(user)" class="user hover-pointer" v-for="(user, index) in filteredUsers"
                   :key="`workspace-users-${index}`">
            <template v-slot:aside>
              <b-img rounded="circle" :src="user.image_url || '/assets/img/avatar.svg'" height="30" width="30"/>
            </template>
            <h5 class="mt-0">{{ user.displayName }}</h5>
          </b-media>
        </b-card-body>
        <NoDataAvailable v-if="usersFilterKeyword && !isFilteredUsers"/>
        <input-widget class=" mt-2" :model="model" attribute="allUser" type="checkbox" @change="onCheckboxClick"/>
      </b-form>
    </b-modal>
  </ValidationObserver>
</template>

<script>
import {createNamespacedHelpers} from "vuex";
import InputWidget from "../../../core/components/input-widget/InputWidget";
import WorkspaceInviteModel from "./WorkspaceInviteModel";
import NoDataAvailable from "../../../core/components/NoDataAvailable";

const {mapState: mapWorkspaceState, mapActions: mapWorkspaceActions} = createNamespacedHelpers('workspace')
export default {
  name: "WorkspaceInviteModal",
  components: {NoDataAvailable, InputWidget},
  data() {
    return {
      model: new WorkspaceInviteModel(),
      isDisabled: false,
      usersFilterKeyword: '',
      selectedUsers: {},
    }
  },
  computed: {
    ...mapWorkspaceState({
      showModal: state => state.view.inviteModal.show,
      users: state => state.view.inviteModal.users,
    }),
    isSelectedUsers() {
      return Object.keys(this.selectedUsers).length > 0;
    },
    isSelectedCheckbox() {
      return this.model.allUser.length > 0
    },
    isFilteredUsers() {
      return this.filteredUsers.length > 0
    },
    filteredUsers() {
      if (!this.usersFilterKeyword || this.isSelectedCheckbox) {
        return [];
      }
      const keyword = this.usersFilterKeyword.toLowerCase();
      return this.users.filter(u =>
        u.displayName.toLowerCase().includes(keyword) &&
        !Object.keys(this.selectedUsers).includes(u.id.toString())
      );
    }
  },
  methods: {
    ...mapWorkspaceActions(['hideInviteModal', 'inviteUsers']),
    onHideModal() {
      this.hideInviteModal();
      this.isDisabled = false;
      this.selectedUsers = {};
      this.usersFilterKeyword = '';
      this.model = new WorkspaceInviteModel();
    },
    async onSubmit() {
      if (this.isSelectedUsers) {
        this.model.selectedUsers = Object.keys(this.selectedUsers)
      }
      if (this.model.allUser.length > 0) {
        this.model.allUser = this.model.allUser.map(a => a.id)
      }
      this.model.workspace_id = this.$route.params.id;
      const res = await this.inviteUsers({...this.model.toJSON()});
      if (res.success) {
        this.$toast(this.$t(`User(s) invited successfully`));
        this.onHideModal();
      } else {
        this.$toast(res.body, 'danger');
      }
    },
    onCheckboxClick() {
      this.isDisabled = !this.isDisabled;
      this.selectedUsers = {};
      this.usersFilterKeyword = '';
      this.model.allUser = this.isDisabled ? this.users : [];
    },
    onUserClick(user) {
      this.selectedUsers = {
        ...this.selectedUsers,
        [user.id]: user
      };
    },
    onRemoveClick(user) {
      delete this.selectedUsers[user.id];
      this.selectedUsers = {...this.selectedUsers};
    }
  },
}
</script>

<style lang="scss" scoped>
@import '../../../core/scss/variables';

.user {
  padding: 0.5rem 1rem;
}

.user-name {
  text-overflow: ellipsis;
  overflow: hidden;
}

.close-button {
  position: absolute;
  top: -5px;
  right: 10px;
}
</style>