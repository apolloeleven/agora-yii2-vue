<template>
  <div class="row">
    <div class="col-md-4">
      <div class="login-left">
        <img src="/assets/img/apollo11-white.png" alt="" style="width: 80px"/>
        <h3>{{ $t('Welcome') }}</h3>
        <p>You are 30 seconds away from entering in <b>Agora!</b></p>
        <router-link class="btn btn-light btn-secondary btn-block" :to="{name: 'auth.login'}">Login</router-link>
      </div>
    </div>
    <div class="col-md-8 col-right">
      <div class="login-right clearfix">
        <div v-if="loading" class="content-spinner text-center text-info">
          <b-spinner class="align-middle"></b-spinner>
          <strong>{{ $t('Please wait...') }}</strong>
        </div>
        <h3 class="login-heading">{{ $t('Create an account') }}</h3>
        <br>
        <div class="login-form">
          <ValidationObserver ref="form" v-slot="{ handleSubmit, invalid ,reset}">
            <b-form @submit.prevent="handleSubmit(onSubmit)" novalidate>
              <input-widget :model="model" attribute="email" disabled/>
              <input-widget :model="model" attribute="firstname"/>
              <input-widget :model="model" attribute="lastname"/>
              <input-widget type="password" :model="model" attribute="password" vid="password"/>
              <input-widget type="password" :model="model" attribute="password_repeat" vid="password_repeat"/>
              <button class="btn btn-primary btn-action">Register</button>
            </b-form>
          </ValidationObserver>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import InputWidget from "../../core/components/input-widget/InputWidget";
import RegisterForm from "./RegisterForm";
import auth from '../../core/services/authService';
import invitationService from "../User/Invitation/invitationService";

export default {
  name: "Register",
  components: {InputWidget},
  data() {
    return {
      loading: false,
      model: new RegisterForm(),
    }
  },
  methods: {
    async onSubmit() {
      this.loading = true;
      const response = await auth.register(this.model);
      this.loading = false;
      if (response.success) {
        this.$toast(this.$t(`Your account will be reviewed by admin and you will receive login instructions`));
        this.$router.push('/login');
      } else {
        this.model.setMultipleErrors(response.body);
      }
    },
  },
  async mounted() {
    this.model.token = this.$route.params.token;
    const res = await invitationService.getEmailByToken(this.model.token);
    if (res.success) {
      this.model.email = res.body;
    }
  },
}
</script>

<style scoped lang="scss">
</style>
