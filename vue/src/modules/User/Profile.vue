<template>
  <div class="user-profile-wrapper h-100">
    <content-spinner :show="userProfile.loading" :text="$t('Please wait...')" class="h-100"/>
    <div class="page-header" v-if="!userProfile.loading && userProfile.loaded">
      <b-button variant="info" @click="onUpdateClick" class="mr-4">
        {{ $t('Update') }}
      </b-button>
    </div>

    <ValidationObserver ref="loginForm">
      <form v-if="!userProfile.loading && userProfile.loaded" v-on:submit.prevent="onUpdateClick" class="mr-4 ml-4">
        <div class="row">
          <div class="col-sm-12 col-md-6">
            <div class="card card-avatar">
              <div class="card-header">
                <h3 class="m-0">{{ $t('Profile image') }}</h3>
              </div>
              <div class="card-body">
                <div class="rounded-circle mt-5">
                  <img alt="profile image" :src="userProfile.image"/>
                </div>
              </div>
            </div>
          </div>

          <div class="col-sm-12 col-md-6">
            <div class="card card-account h-100">
              <div class="card-header">
                <h3 class="m-0">{{ $t('Account') }}</h3>
              </div>
              <div class="card-body">
                <div class="row">
                  <div class="col col-sm-12">
                    <input-widget :model="userModel" attribute="email" type="email"/>
                  </div>
                </div>
                <div class="row">
                  <div class="col col-sm-6">
                    <input-widget :model="userModel" attribute="password" type="password" vid="password"/>
                  </div>
                  <div class="col col-sm-6">
                    <input-widget :model="userModel" attribute="confirmPassword" type="password" vid="confirmPassword"/>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-12 col-md-12 mt-4">
            <div class="row">
              <div class="col col-sm-12">
                <div class="card card-information">
                  <div class="card-header">
                    <h3 class="m-0">{{ $t('Information') }}</h3>
                  </div>
                  <div class="card-body">
                    <div class="row">
                      <div class="col col-sm-6">
                        <input-widget :model="userModel" attribute="firstName" type="text"/>
                      </div>
                      <div class="col col-sm-6">
                        <input-widget :model="userModel" attribute="lastName" type="text"/>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col col-sm-6">
                        <input-widget :model="userModel" attribute="mobile" type="text"/>
                      </div>
                      <div class="col col-sm-6">
                        <input-widget :model="userModel" attribute="phone" type="text"/>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col col-sm-6">
                        <input-widget :model="userModel" attribute="birthday" type="date"/>
                      </div>
                      <div class="col col-sm-6">
                        <input-widget :model="userModel" attribute="hobbies" :multiselect-options='userModel.hobbies'
                                      type="multiselect"/>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-6">
                        <input-widget v-if="userModel" :model="userModel" attribute="aboutMe" type="richtext"/>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </form>
    </ValidationObserver>
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

        // We need to set timeout, because the data from backend comes faster then the CkEditor rendered.
        // TODO Need optimization!
        setTimeout(() => {
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
        }, 500);
      }
    }
  },
  mounted() {
    this.setProfile();
  }
}

</script>

<style scoped lang="scss">

.page-header {
  height: 60px;
  display: flex;
  justify-content: flex-end;

  button {
    align-self: center;
  }
}

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
</style>