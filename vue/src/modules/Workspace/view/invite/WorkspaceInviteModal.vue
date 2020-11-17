<template>
  <ValidationObserver ref="form" v-slot="{ handleSubmit, invalid ,reset}">
    <b-modal id="invite-modal" :visible="showModal" ref="inviteModal" :title='$t(`New Workspace Invitation`)' scrollable
             @shown="onShown" @hidden="onHideModal" @ok.prevent="handleSubmit(onSubmit)" :ok-title="$t('Submit')">
      <b-form @submit.prevent="handleSubmit(onSubmit)" novalidate>
        <input-widget ref="emailInput" :model="model" attribute="name"/>
      </b-form>
    </b-modal>
  </ValidationObserver>
</template>

<script>
import InputWidget from "@/core/components/input-widget/InputWidget";
import {createNamespacedHelpers} from "vuex";
import WorkspaceInviteModel from "./WorkspaceInviteModel";

const {mapState: mapWorkspaceState, mapActions: mapWorkspaceActions} = createNamespacedHelpers('workspace')
export default {
  name: "WorkspaceInviteModal",
  components: {InputWidget},
  data() {
    return {
      model: new WorkspaceInviteModel()
    }
  },
  computed: {
    ...mapWorkspaceState({
      showModal: state => state.view.invite.modal.show
    }),
  },
  methods: {
    ...mapWorkspaceActions(['hideInviteModal']),
    onShown() {

    },
    onHideModal() {
      this.hideInviteModal();
    },
    onSubmit() {

    },
  },
}
</script>

<style scoped>

</style>