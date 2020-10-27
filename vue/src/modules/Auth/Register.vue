<template>
  <div class="row">
    <div class="col-md-4">
      <div class="auth-left mb-3">
        <img src="/assets/img/apollo11-white.png" alt="" style="width: 80px"/>
        <h3>{{ $t('Welcome') }}</h3>
        <p>You are 30 seconds away from entering in <b>Agora!</b></p>
        <router-link class="btn btn-light btn-secondary btn-block" :to="{name: 'auth.login'}">Login</router-link>
      </div>
    </div>
    <div class="col-md-8 col-right">
      <div class="auth-right clearfix">
        <content-spinner :show="loading" :text="$t('Please wait...')" class="h-100"/>
        <h3 class="auth-heading">{{ $t('Create an account') }}</h3>
        <br>
        <div class="auth-form">
          <ValidationObserver ref="form" v-slot="{ handleSubmit, invalid ,reset}">
            <b-form @submit.prevent="handleSubmit(onSubmit)" novalidate>
              <input-widget :model="model" attribute="email" disabled/>
              <input-widget :model="model" attribute="firstname"/>
              <input-widget :model="model" attribute="lastname"/>
              <input-widget type="password" :model="model" attribute="password" vid="password"/>
              <input-widget type="password" :model="model" attribute="password_repeat" vid="password_repeat"/>
              <button :disabled="loading" class="btn btn-primary btn-action">Register</button>
            </b-form>
          </ValidationObserver>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import InputWidget from "../../core/components/input-widget/InputWidget";
import auth from '../../core/services/authService';
import ContentSpinner from "../../core/components/ContentSpinner";
import RegisterModel from "./RegisterModel";
import httpService from "../../core/services/httpService";

export default {
  name: "Register",
  components: {ContentSpinner, InputWidget},
  data() {
    return {
      loading: false,
      model: new RegisterModel(),
    }
  },
  methods: {
    async onSubmit() {
      this.loading = true;
      this.model.token = this.$route.params.token;
      const response = await auth.register({...this.model.toJSON()});
      this.loading = false;
      if (response.success) {
        this.$toast(this.$t(`Your account will be reviewed by admin and you will receive login instructions`));
        this.$router.push({name: 'auth.login'});
      } else {
        this.$toast(this.$t(`Unable to register user`), 'danger');
      }
    },
    async getEmail() {
      const res = await httpService.get(`/v1/users/invitation/get-email?token=${this.$route.params.token}`);
      if (res.success) {
        this.model.email = res.body;
      }
    }
  },
  mounted() {
    this.getEmail()
  },
}
</script>

<style scoped lang="scss">
</style>
