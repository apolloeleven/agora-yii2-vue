<template>
  <ValidationObserver ref="form" v-slot="{ handleSubmit, invalid ,reset}">
    <b-modal modal-class="workspace-invite" size="lg" id="invite-modal" :visible="showModal" ref="inviteModal"
             :title='$t(`Add users to workspace`)' @hidden="onHideModal" @ok.prevent="handleSubmit(onSubmit)"
             :ok-title="$t('Submit')" :ok-disabled="!model.selectedUsers.length" ok-only scrollable>
      <b-form @submit.prevent="handleSubmit(onSubmit)" novalidate>
        <div class="row">
          <div class="col-9">
            <input-widget :model="model"
                          :disabled="isDisabled"
                          attribute="selectedUsers"
                          type="multiselect"
                          :multiselect-options="userOptions"
                          :placeholder="$t('Search for users...')"/>
          </div>
          <div class="col-3">
            <input-widget size="lg" :model="model" attribute="role" type="select" :select-options="dropdownData.userRoles"/>
          </div>
        </div>
        <input-widget :model="model" attribute="allUser" type="checkbox"/>
      </b-form>
    </b-modal>
  </ValidationObserver>
</template>

<script>
import {createNamespacedHelpers} from "vuex";
import InputWidget from "../../../core/components/input-widget/InputWidget";
import WorkspaceInviteModel from "./WorkspaceInviteModel";

const {mapState: mapWorkspaceState, mapActions: mapWorkspaceActions} = createNamespacedHelpers('workspace')
const {mapState} = createNamespacedHelpers('employee')
export default {
  name: "WorkspaceInviteModal",
  components: {InputWidget},
  data() {
    return {
      model: new WorkspaceInviteModel(),
      isDisabled: false,
    }
  },
  computed: {
    ...mapWorkspaceState({
      showModal: state => state.view.inviteModal.show,
      users: state => state.view.inviteModal.users,
    }),
    ...mapState({
      dropdownData: state => state.modalDropdownData
    }),
    userOptions() {
      return this.users.map(u => this.convertToUserOption(u))
    },
  },
  watch: {
    'model.allUser'() {
      if (this.model.allUser) {
        this.model.selectedUsers = this.users.map(u => this.convertToUserOption(u));
      } else {
        this.model.selectedUsers = [];
      }
    }
  },
  methods: {
    ...mapWorkspaceActions(['hideInviteModal', 'inviteUsers']),
    convertToUserOption: (u) => ({
      text: u.display_name,
      value: u.id,
      img: u.image_url ? u.image_url : '/assets/img/avatar.svg'
    }),
    onHideModal() {
      this.hideInviteModal();
      this.isDisabled = false;
      this.model = new WorkspaceInviteModel();
    },
    async onSubmit() {
      if (this.model.selectedUsers.length > 0) {
        this.model.selectedUsers = this.model.selectedUsers.map(s => s.value)
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
      // this.isDisabled = !this.isDisabled;

      // this.model.allUser = this.isDisabled ? this.users : [];
    },
  },
}
</script>

<style lang="scss">
#invite-modal {
  .modal-body {
    height: 400px;
  }
}
</style>