<template>
  <ValidationObserver ref="form" v-slot="{ handleSubmit, invalid ,reset}">
    <b-modal
      id="user-form"
      :visible="invitationModal.show"
      ref="modal"
      :title='$t(`New Invitation`)'
      @shown="onShown"
      @hidden="onHideModal"
      @ok.prevent="handleSubmit(onSubmit)" :ok-disabled="loading" :ok-title="$t('Submit')" scrollable>
      <content-spinner :show="loading" :text="$t('Please wait...')" :fullscreen="true" class="h-100"/>
      <b-form @submit.prevent="handleSubmit(onSubmit)" novalidate>
        <input-widget ref="emailInput" :model="model" attribute="email"/>
      </b-form>
    </b-modal>
  </ValidationObserver>
</template>

<script>

import {createNamespacedHelpers} from "vuex";
import UserInvitationFormModel from "./UserInvitationFormModel";
import InputWidget from "../../../core/components/input-widget/InputWidget";
import ContentSpinner from "../../../core/components/ContentSpinner";

const {mapState, mapActions} = createNamespacedHelpers('setup');

export default {
  name: "UserInvitationForm",
  components: {ContentSpinner, InputWidget},
  data() {
    return {
      loading: false,
      model: new UserInvitationFormModel(),
    }
  },
  computed: {
    ...mapState(['invitationModal'])
  },
  methods: {
    ...mapActions(['hideInvitationModal', 'inviteUser']),
    checkValidity: (touched, validated, valid) => {
      return (!touched && !validated) ? null : valid;
    },
    onShown() {
      this.$refs.emailInput.focus()
    },
    onHideModal() {
      this.model.email = null;
      this.hideInvitationModal();
    },
    async onSubmit() {
      this.model.resetErrors();
      this.loading = true;
      const res = await this.inviteUser(this.model.email);
      this.loading = false;
      if (res.success) {
        this.$toast(this.$t(`Email '{email}' was successfully invited`, {email: this.model.email}));
        this.hideInvitationModal();
      } else {
        this.model.setMultipleErrors([{field: 'email', message: res.body.message}]);
      }
    },
  },
}
</script>

<style scoped>

</style>