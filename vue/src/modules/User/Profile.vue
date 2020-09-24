<template>
  <div class="user-profile-wrapper">
    <content-spinner :show="userProfile.loading" :text="$t('Please wait...')" class="h-100"/>
    <form v-if="!userProfile.loading && userProfile.loaded" v-on:submit.prevent="onUpdateClick">
      <ValidationObserver ref="loginForm">
        <div class="profile-content p-3">
          <div class="col-md-6 border-right">
            <div class="p-3">
              <h4 class="text-left">Account Settings</h4>
              <div class="rounded-circle mt-5">
                <img alt="profile image" :src="userProfile.image"/>
              </div>
              <div class="row mt-4">
                <div class="col-md-12 mb-4">
                  <input-widget :model="userModel" attribute="email" type="email"/>
                </div>
                <div class="col-md-6">
                  <input-widget :model="userModel" attribute="password" type="password" vid="password"/>
                </div>
                <div class="col-md-6">
                  <input-widget :model="userModel" attribute="confirmPassword" type="password" vid="confirmPassword"/>
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
                  <input-widget :model="userModel" attribute="aboutMe" type="richtext"/>
                </div>
                <div class="col-md-12 mb-4">
                  <input-widget :model="userModel" attribute="hobbies" :multiselect-options='userModel.hobbies'
                                type="multiselect"/>
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
  </div>
</template>

<script>
import {createNamespacedHelpers} from 'vuex';
import UserModel from "@/modules/User/UserModel";
import InputWidget from "@/core/components/input-widget/InputWidget";
import ContentSpinner from "@/core/components/ContentSpinner";

const {mapState, mapActions} = createNamespacedHelpers('user');
export default {
  name: "Profile",
  components: {InputWidget, ContentSpinner},
  data() {
    return {
      userModel: new UserModel(),
      multiselectOptions: []
    }
  },
  computed: {
    ...mapState(['userProfile']),
  },
  methods: {
    ...mapActions(['getProfile', 'updateProfile']),
    onUpdateClick() {
      this.userModel.resetErrors();
      this.beforeSubmit();
      this.updateProfile(this.userModel);
      this.setProfile();
    },
    beforeSubmit() {
      this.userModel.hobbies = this.userModel.hobbies.map(ob => ob.value);
    },
    async setProfile() {
      let response = await this.getProfile();
      if (response.success) {
        const user = JSON.parse(localStorage.getItem('CURRENT_USER'));

        this.userModel.email = user.email;
        this.userModel.firstName = this.userProfile.data.first_name || '';
        this.userModel.lastName = this.userProfile.data.last_name || '';
        this.userModel.birthday = this.userProfile.data.birthday || '';
        this.userModel.phone = this.userProfile.data.phone || '';
        this.userModel.mobile = this.userProfile.data.mobile || '';
        this.userModel.aboutMe = this.userProfile.data.about_me || '';

        this.userModel.hobbies = this.userProfile.data.hobbies ? this.userProfile.data.hobbies.map(op => ({
          value: op,
          text: this.$t(op)
        })) : [];

        this.userModel.password = '';
        this.userModel.confirmPassword = '';
      }
    }
  },
  mounted() {
    this.setProfile();
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