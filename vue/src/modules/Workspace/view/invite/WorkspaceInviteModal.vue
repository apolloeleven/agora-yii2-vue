<template>
  <ValidationObserver ref="form" v-slot="{ handleSubmit, invalid ,reset}">
    <b-modal modal-class="workspace-invite" id="invite-modal" :visible="showModal" ref="inviteModal"
             :title='$t(`New Workspace Invitation`)' @hidden="onHideModal" @ok.prevent="handleSubmit(onSubmit)"
             :ok-title="$t('Submit')" :ok-disabled="!selected" scrollable>
      <b-form @submit.prevent="handleSubmit(onSubmit)" novalidate>
        <input-widget :model="model" :disabled="isDisabled" attribute="selectedUsers" type="multiselect"
                      :multiselect-options="userOptions" :placeholder="$t('Invite Users')">
        </input-widget>
        <input-widget :model="model" attribute="allUser" type="checkbox" @change="onCheckboxClick"/>
      </b-form>
    </b-modal>
  </ValidationObserver>
</template>

<script>
import {createNamespacedHelpers} from "vuex";
import InputWidget from "../../../../core/components/input-widget/InputWidget";
import WorkspaceInviteModel from "./WorkspaceInviteModel";

const {mapState: mapWorkspaceState, mapActions: mapWorkspaceActions} = createNamespacedHelpers('workspace')
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
      showModal: state => state.view.invite.modal.show,
      users: state => state.view.invite.modal.users,
    }),
    userOptions() {
      return this.users.map(u => ({text: u.displayName, value: u.id}))
    },
    selected() {
      return this.model.allUser.length > 0 || this.model.selectedUsers.length > 0;
    },
  },
  methods: {
    ...mapWorkspaceActions(['hideInviteModal', 'inviteUsers']),
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
        this.$toast(this.$t(`Users invited successfully`));
        this.onHideModal();
      } else {
        this.model.setMultipleErrors(res.body);
      }
    },
    onCheckboxClick() {
      this.isDisabled = !this.isDisabled;
      this.model.selectedUsers = [];
      this.model.allUser = this.isDisabled ? this.users : [];
    },
  },
}
</script>

<style lang="scss" scoped>

</style>