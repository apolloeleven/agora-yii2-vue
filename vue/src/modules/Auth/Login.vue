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
        <div class="login-form">
          <div class="form-group">
            <input type="text" class="form-control" placeholder="Username" value=""/>
          </div>
          <div class="form-group">
            <input type="password" class="form-control" placeholder="Password" value=""/>
          </div>
          <button class="btn btn-primary btn-action" @click="onLoginClick()">Login</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "Login",
  methods: {
    onLoginClick() {
      this.$router.push('/')
    }
  }
}
</script>

<style scoped lang="scss">
</style>
