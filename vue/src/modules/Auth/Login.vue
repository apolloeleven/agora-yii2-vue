<template>
  <div class="row">
    <div class="col-md-4">
      <div class="login-left">
        <img src="/assets/img/apollo11-white.png" alt="" style="width: 80px"/>
        <h3>Welcome</h3>
        <p>You are 30 seconds away from entering in <b>Agora!</b></p>
        <router-link class="btn btn-light btn-secondary btn-block" to="/auth/register">Register</router-link>
      </div>
    </div>
    <div class="col-md-8 col-right">
      <div class="login-right clearfix">
        <h3 class="login-heading">Login to your account</h3>
        <br>
        <form v-on:submit.prevent="onLoginClick">
          <ValidationObserver ref="loginForm">
            <div class="login-form">
              <input-widget ref="usernameInputWidget" :model="loginFormModel" attribute="username"/>
              <input-widget :model="loginFormModel" attribute="password" type="password"/>
              <div class="d-flex align-items-center justify-content-between">
                <button class="btn btn-primary mr-2">{{ $t('Login') }}</button>
                <router-link :to="{name: 'reset-password'}">{{ $t('Request new password') }}</router-link>
              </div>
            </div>
          </ValidationObserver>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
import auth from '../../core/services/authService';
import LoginForm from "./LoginForm";
import InputWidget from "../../core/components/input-widget/InputWidget";

export default {
  name: "Login",
  components: {InputWidget},
  data() {
    return {
      loginFormModel: new LoginForm(),
    }
  },
  methods: {
    async onLoginClick() {
      this.loginFormModel.resetErrors();
      let response = await auth.login(this.loginFormModel);

      if (response.success) {
        if (auth.getRedirectTo()) {
          this.$router.push(auth.getRedirectTo());
          auth.removeRedirectTo();
        } else {
          this.$router.push('/');
        }
      } else {
        this.loginFormModel.setMultipleErrors(response.body);
      }
    }
  },
  mounted() {
    setTimeout(() => {
      this.$refs.usernameInputWidget.focus()
    }, 500)
  }
}
</script>

<style scoped lang="scss">
</style>