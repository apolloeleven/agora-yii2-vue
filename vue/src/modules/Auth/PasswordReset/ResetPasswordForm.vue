<template>
  <div class="row">
    <div class="col-md-4">
      <div class="login-left">
        <img src="/assets/img/apollo11-white.png" alt="" style="width: 80px"/>
        <h3>Welcome</h3>
      </div>
    </div>
    <div class="login-right clearfix">
      <div style="position: relative">
        <div v-if="loading" class="content-spinner text-center text-info">
          <b-spinner class="align-middle"></b-spinner>
          <strong>{{ $t('Please wait...') }}</strong>
        </div>
        <h3 class="login-heading">{{ $t('Password Reset') }}</h3>
        <br>
        <div class="login-form">
          <ValidationObserver ref="form" v-slot="{ handleSubmit, invalid, reset}">
            <b-form @submit.prevent="handleSubmit(onSubmit)" novalidate>
              <input-widget type="password" :model="model" attribute="password" vid="password"/>
              <input-widget type="password" :model="model" attribute="repeat_password" vid="repeat_password"/>
              <div class="d-flex align-items-center justify-content-between">
                <button :disabled="loading" class="btn btn-primary btn-action">{{ $t('Submit') }}</button>
                <router-link class="float-right" :to="{name: 'auth.login'}">
                  {{ $t('Go to Login') }}
                </router-link>
              </div>
            </b-form>
          </ValidationObserver>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import auth from '../../../core/services/authService';
import InputWidget from "../../../core/components/input-widget/InputWidget";
import PasswordResetForm from "./PasswordResetForm";

export default {
  name: "ResetPasswordForm",
  components: {InputWidget},
  data() {
    return {
      loading: false,
      model: new PasswordResetForm(),
    }
  },
  methods: {
    async onSubmit() {
      this.model.resetErrors();
      this.loading = true;
      let response = await auth.passwordReset(this.model);
      this.loading = false;
      if (response.success) {
        this.$notify({
          group: 'success',
          type: 'success',
          title: this.$t('Success'),
          text: this.$t(`Password was successfully changed`),
          speed: 1000,
        });
        this.$router.push('/login');
      } else {
        this.model.setMultipleErrors(response.body);
      }
    },
  },
  async mounted() {
    this.model.token = this.$route.params.token;
    const response = await auth.checkToken(this.model.token);
    if (!response.success) {
      this.$router.push('/login');
    }
  }
}
</script>

<style scoped lang="scss">

.content-spinner {
  position: absolute;
  left: 0;
  right: 0;
  bottom: 0;
  top: 0;
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 1000;
  background-color: rgba(255, 255, 255, 1);
  flex-direction: column;
}

</style>
