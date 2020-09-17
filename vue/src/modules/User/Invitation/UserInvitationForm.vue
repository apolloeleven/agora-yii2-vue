<template>
  <ValidationObserver ref="form" v-slot="{ handleSubmit, invalid ,reset}">
    <b-modal
        :visible="showModal" id="user-form" ref="modal" :title='$t(`New Invitation`)' @hidden="onHideModal"
        @ok.prevent="handleSubmit(onSubmit)" :ok-disabled="loading" :ok-title="$t('Submit')" scrollable>
      <div v-if="loading" class="content-spinner text-center text-info my-2">
        <b-spinner class="align-middle"></b-spinner>
        <strong>{{ $t('Please wait...') }}</strong>
      </div>
      <b-form @submit.prevent="handleSubmit(onSubmit)" @reset="onReset" novalidate>
        <validation-provider name="Email" rules="required|email" v-slot="{ touched, validated, valid, errors, }">
          <b-form-group :label="$t('Email Address')">
            <b-form-input v-model="email"
                          autocapitalize="off"
                          :state="checkValidity(touched, validated, valid)"
                          :placeholder="$t('Enter email address to invite the user')"/>
            <b-form-invalid-feedback>
              <span>{{ errors[0] }}</span>
            </b-form-invalid-feedback>
          </b-form-group>
        </validation-provider>
      </b-form>
    </b-modal>
  </ValidationObserver>
</template>

<script>

import {createNamespacedHelpers} from "vuex";

const {mapState: mapStateInvitations, mapActions} = createNamespacedHelpers('invitations');

export default {
  name: "UserInvitationForm",
  data() {
    return {
      loading: false,
      email: null
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
      this.email = null;
      this.hideModal();
    },
    async onSubmit() {
      this.loading = true;
      const res = await this.inviteUser(this.email);
      this.loading = false;
      if (res.success) {
        this.$notify({
          group: 'success',
          type: 'success',
          title: this.$t('Success'),
          text: this.$t(`Email "{email}" was successfully invited`, {email: this.email})
        });
        this.$nextTick(() => {
          this.hideModal();
        });
      } else {
        console.log(res.errors)
      }
    },
    onReset() {
      console.log("reset");
    }
  },
  created() {

  }
}
</script>

<style scoped>

</style>