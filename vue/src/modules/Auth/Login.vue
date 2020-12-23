<template>
  <div class="row">
    <div class="col-md-4">
      <div class="auth-left mb-3">
        <img src="/assets/img/apollo11-white.png" alt="" style="width: 80px"/>
        <h3>{{ $t('Welcome') }}</h3>
        <p>You are 30 seconds away from entering in <b>Agora!</b></p>
      </div>
    </div>
    <div class="col-md-8 col-right">
      <div class="auth-right clearfix">
        <h3 class="auth-heading text-left">{{ $t('Login to your account') }}</h3>
        <br>
        <form v-on:submit.prevent="onLoginClick">
          <h4 class="text-left">Sample users</h4>
          <hr>
          <pre class="text-left rounded">Email: user1@agoraintra.net
Password: 123456 <br>
Email: user2@agoraintra.net
Password: 123456 <br>
Email: user3@agoraintra.net
Password: 123456</pre>
          <ValidationObserver ref="loginForm">
            <div class="auth-form">
              <input-widget ref="emailInputWidget" :model="model" attribute="email"/>
              <input-widget :model="model" attribute="password" type="password"/>
              <div class="d-flex align-items-center justify-content-between">
                <button :disabled="loading" class="btn btn-primary mr-2">
                  <b-spinner v-if="loading" small/>
                  {{ $t('Login') }}
                </button>
                <router-link :to="{name: 'request-password-reset'}">{{ $t('Request new password') }}</router-link>
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
import LoginModel from "./LoginModel";
import InputWidget from "../../core/components/input-widget/InputWidget";
import ContentSpinner from "@/core/components/ContentSpinner";

export default {
  name: "Login",
  components: {InputWidget},
  data() {
    return {
      loading: false,
      model: new LoginModel(),
    }
  },
  methods: {
    async onLoginClick() {
      this.loading = true;
      this.model.resetErrors();
      let response = await auth.login(this.model);
      this.loading = false;
      if (response.success) {
        if (auth.getRedirectTo()) {
          this.$router.push(auth.getRedirectTo());
          auth.removeRedirectTo();
        } else {
          this.$router.push('/');
        }
      } else {
        this.model.setMultipleErrors([{field: 'password', message: response.body.password}]);
      }
    }
  },
  mounted() {
    setTimeout(() => {
      this.$refs.emailInputWidget.focus()
    }, 500)
  }
}
</script>

<style scoped lang="scss">
.col-right {
  position: relative;
}
</style>