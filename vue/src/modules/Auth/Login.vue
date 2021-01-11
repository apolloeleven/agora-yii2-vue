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
        <h3 class="auth-heading">{{ $t('Login to your account') }}</h3>
        <br>
        <form v-on:submit.prevent="onLoginClick">
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