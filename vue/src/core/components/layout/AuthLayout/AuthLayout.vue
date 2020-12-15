<template>
  <div id="auth-layout">
    <div id="auth">
      <div class="container">
        <div class="form-wrapper">
          <router-view/>
        </div>
      </div>
    </div>
  </div>
</template>

<script>

export default {
  name: "AuthLayout",
}
</script>

<style lang="scss" scoped>
@import "../../../scss/variables";

#auth-layout {
  display: flex;
  width: 100%;
  height: 100%;
}

@-webkit-keyframes mover {
  0% {
    transform: translateY(0);
  }
  100% {
    transform: translateY(-20px);
  }
}

@keyframes mover {
  0% {
    transform: translateY(0);
  }
  100% {
    transform: translateY(-20px);
  }
}

#auth {
  width: 680px;
  margin: 0 auto;
  display: flex;
  align-items: center;


  .form-wrapper {
    padding: 50px;
    width: 100%;
    position: relative;
    box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.5);
    background: -webkit-linear-gradient(left, $primary, $primary);
    @media (max-width: 767px) {
      padding: 20px;
    }
  }

  & /deep/ .btn-action {
    float: right;
    width: 160px;
  }

  & /deep/ .auth-left {
    text-align: center;
    color: #fff;
    margin-top: 4%;

    input {
      border: none;
      border-radius: 1.5rem;
      padding: 2%;
      width: 60%;
      background: #f8f9fa;
      font-weight: bold;
      color: #383d41;
      margin-bottom: 3%;
      cursor: pointer;
    }

    img {
      margin-top: 15%;
      margin-bottom: 5%;
      width: 25%;
      -webkit-animation: mover 2s infinite alternate;
      animation: mover 1s infinite alternate;
    }
  }

  & /deep/ .col-right {
    display: flex;
    align-items: center;
  }

  & /deep/ .auth-right {
    flex: 1;
    padding: 30px 50px;
    background: #f8f9fa;
    border-top-left-radius: 10% 50%;
    border-bottom-left-radius: 10% 50%;
  }

  & /deep/ .auth-heading {
    text-align: center;
    color: #495057;
  }

  @media (max-width: 767px) {
    & /deep/ .auth-right {
      padding: 20px;
      border-radius: 6px;
    }
  }

}

</style>
