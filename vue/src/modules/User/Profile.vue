<template>
  <form v-on:submit.prevent="onUpdateClick">
    <ValidationObserver ref="loginForm">
      <div class="profile-content p-3">
        <div class="col-md-6 border-right">
          <div class="p-3">
            <h4 class="text-left">Account Settings</h4>
            <div class="rounded-circle mt-5">
              <img alt="profile image" :src="user.image"/>
              <font-awesome-icon :icon="['fas', 'pencil-alt']"></font-awesome-icon>
            </div>
            <div class="row mt-4">
              <div class="col-md-12 mb-4">
                <input-widget :model="userModel" attribute="email" type="email"/>
              </div>
              <div class="col-md-6">
                <input-widget :model="userModel" attribute="password" type="password"/>
                <!--            <b-input ref="password"-->
                <!--                     :name='`password`'-->
                <!--                     :key='`password`'-->
                <!--                     v-model="user.password" class="mr-sm-2"-->
                <!--                     placeholder="Password"/>-->
              </div>
              <div class="col-md-6">
                <input-widget :model="userModel" attribute="confirmPassword" type="password"/>
                <!--            <b-input ref="confirmPassword"-->
                <!--                     :name='`confirmPassword`'-->
                <!--                     :key='`confirmPassword`'-->
                <!--                     v-model="user.confirmPassword" class="mr-sm-2"-->
                <!--                     placeholder="Confirm Password"/>-->
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="p-3 ">
            <h4 class="text-left">Profile Settings</h4>
            <div class="row mt-4">
              <div class="col-md-6 mb-4">
                <input-widget :model="userModel" attribute="firstName" type="text"/>
              </div>
              <div class="col-md-6">
                <input-widget :model="userModel" attribute="lastName" type="text"/>
              </div>
              <div class="col-md-6 mb-4">
                <input-widget :model="userModel" attribute="mobile" type="text"/>
              </div>
              <div class="col-md-6">
                <input-widget :model="userModel" attribute="phone" type="text"/>
              </div>
              <div class="col-md-12 mb-4">
                <input-widget :model="userModel" attribute="birthday" type="date"/>
              </div>
              <div class="col-md-12 mb-4">
                <input-widget :model="userModel" attribute="aboutMe" type="textarea"/>
              </div>
              <div class="col-md-12 mb-4 d-flex justify-content-end">
                <button class="btn btn-primary mr-2">{{ $t('Update') }}</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </ValidationObserver>
  </form>
</template>

<script>
import {createNamespacedHelpers} from 'vuex';
import UserModel from "@/modules/User/UserModel";
import InputWidget from "@/core/components/input-widget/InputWidget";
import auth from "@/core/services/auth.service";

const {mapState, mapActions} = createNamespacedHelpers('user');
export default {
  name: "Profile",
  components: {InputWidget},
  data() {
    return {
      userModel: new UserModel(),
    }
  },
  computed: {
    ...mapState(['user']),
  },
  methods: {
    ...mapActions(['setUser', 'updateUser']),
    async onUpdateClick() {
      this.userModel.resetErrors();
      let response = await auth.updateProfile(this.userModel);


    },
    async getUserProfile() {
      const user = JSON.parse(localStorage.getItem('CURRENT_USER'));
      this.profile = await auth.getProfile(user.id);

      this.userModel.id = user.id;
      this.userModel.email = user.email;
      this.userModel.firstName = this.profile.first_name;
      this.userModel.lastName = this.profile.last_name;
      this.userModel.birthday = this.profile.birthday;
      this.userModel.phone = this.profile.phone;
      this.userModel.mobile = this.profile.mobile;
      this.userModel.aboutMe = this.profile.aboutMe;
    }
  },
  mounted() {
    this.getUserProfile();
  }
}

</script>

<style scoped lang="scss">
.profile-content {
  position: relative;
  display: flex;
  width: 100%;
  justify-content: center;
  align-items: flex-start;
  flex-wrap: wrap;

  .rounded-circle {
    width: 200px;
    height: 200px;
    overflow: hidden;
    margin: 0 auto;
    display: flex;
    justify-content: center;
    align-items: baseline;
    cursor: pointer;
    transition: all .2s;

    svg {
      position: absolute;
      z-index: 9;
      color: #000;
      top: 170px;
      font-size: 30px;
      display: none;
    }

    img {
      width: 100%;
    }

    &:hover {
      svg {
        display: block;
      }

      img {
        opacity: .5;
      }
    }
  }

  h4.text-left {
    width: 100%;
  }
}
</style>