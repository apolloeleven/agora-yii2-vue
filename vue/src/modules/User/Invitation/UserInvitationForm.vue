<template>
  <ValidationObserver ref="form" v-slot="{ handleSubmit, invalid ,reset}">
    <b-modal
        :visible="showModal" id="user-form" ref="modal" :title='$t(`New Invitation`)' @hidden="onHideModal"
        @ok.prevent="handleSubmit(onSubmit)" :ok-disabled="loading" :ok-title="$t('Submit')" scrollable>
      <div v-if="loading" class="content-spinner text-center text-info my-2">
        <b-spinner class="align-middle"></b-spinner>
        <strong>{{ $t('Please wait...') }}</strong>
      </div>
      <b-form @submit.prevent="handleSubmit(onSubmit)" novalidate>
        <input-widget :model="model" attribute="email"/>
      </b-form>
    </b-modal>
  </ValidationObserver>
</template>

<script>

import {createNamespacedHelpers} from "vuex";
import InvitationForm from "./InvitationForm";
import InputWidget from "../../../core/components/input-widget/InputWidget";

const {mapState: mapStateInvitations, mapActions} = createNamespacedHelpers('invitations');

export default {
  name: "UserInvitationForm",
  components: {InputWidget},
  data() {
    return {
      loading: false,
      model: new InvitationForm(),
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
      this.loading = true;
      const res = await this.inviteUser(this.model.email);
      this.loading = false;
      if (res.success) {
        this.$notify({
          group: 'success',
          type: 'success',
          title: this.$t('Success'),
          text: this.$t(`Email '{email}' was successfully invited`, {email: this.model.email})
        });
        this.$nextTick(() => {
          this.hideModal();
        });
      } else {
        this.$notify({
          group: 'error',
          type: 'error',
          title: this.$t('Error'),
          text: this.$t(`Email "{email}" was not invited`, {email: this.model.email})
        });
        this.$nextTick(() => {
          this.hideModal();
        });
      }
    },
  },
}
</script>

<style scoped>

</style>