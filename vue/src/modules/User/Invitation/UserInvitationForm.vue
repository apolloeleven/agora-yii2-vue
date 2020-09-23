<template>
  <ValidationObserver ref="form" v-slot="{ handleSubmit, invalid ,reset}">
    <b-modal
        :visible="showModal" id="user-form" ref="modal" :title='$t(`New Invitation`)' @hidden="onHideModal"
        @ok.prevent="handleSubmit(onSubmit)" :ok-disabled="loading" :ok-title="$t('Submit')" scrollable>
      <content-spinner :show="loading" :text="$t('Please wait...')" :fullscreen="true" class="h-100"/>
      <b-form @submit.prevent="handleSubmit(onSubmit)" novalidate>
        <input-widget :model="model" attribute="email"/>
      </b-form>
    </b-modal>
  </ValidationObserver>
</template>

<script>

import {createNamespacedHelpers} from "vuex";
import UserInvitationFormModel from "./UserInvitationFormModel";
import InputWidget from "../../../core/components/input-widget/InputWidget";
import ContentSpinner from "../../../core/components/ContentSpinner";

const {mapState: mapStateInvitations, mapActions} = createNamespacedHelpers('user/invitations');

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
    ...mapStateInvitations(['showModal', 'modalInvitation'])
  },
  methods: {
    ...mapActions(['hideModal', 'inviteUser']),
    checkValidity: (touched, validated, valid) => {
      return (!touched && !validated) ? null : valid;
    },
    onHideModal() {
      this.model.email = null;
      this.hideModal();
    },
    async onSubmit() {
      this.model.resetErrors();
      this.loading = true;
      const res = await this.inviteUser(this.model.email);
      this.loading = false;
      if (res.success) {
        this.$toast(this.$t(`Email '{email}' was successfully invited`, {email: this.model.email}));
        this.hideModal();
      } else {
        this.model.setMultipleErrors([{field: 'email', message: res.body.message}]);
      }
    },
  },
}
</script>

<style scoped>

</style>