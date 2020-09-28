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
                <div class="mt-2 img-container">
                  <ImageUploader v-model="image" :src="userProfile.data.image_path" ref="uploader"/>
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
                    <input-widget :model="userModel" attribute="confirm_password" type="password" vid="confirm_password"/>
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
                        <input-widget :model="userModel" attribute="first_name" type="text"/>
                      </div>
                      <div class="col col-sm-6">
                        <input-widget :model="userModel" attribute="last_name" type="text"/>
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
                        <input-widget v-if="userModel" :model="userModel" attribute="about_me" type="richtext"/>
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
import ImageUploader from "@/core/components/ImageUploader";

const {mapState, mapActions} = createNamespacedHelpers('user');
export default {
  name: "Profile",
  components: {InputWidget, ContentSpinner, ImageUploader},
  data() {
    return {
      userModel: new UserModel(),
      image: null,
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
      this.userModel.image = this.image;
    },
    async setProfile() {
      let response = await this.getProfile();
      if (response.success) {

        // We need to set timeout, because the data from backend comes faster then the CkEditor rendered.
        // TODO Need optimization!
        setTimeout(() => {
          const user = JSON.parse(localStorage.getItem('CURRENT_USER'));
          this.userModel.email = user.email;
          this.userModel.first_name = this.userProfile.data.first_name || '';
          this.userModel.last_name = this.userProfile.data.last_name || '';
          this.userModel.birthday = this.userProfile.data.birthday || '';
          this.userModel.phone = this.userProfile.data.phone || '';
          this.userModel.mobile = this.userProfile.data.mobile || '';
          this.userModel.about_me = this.userProfile.data.about_me || '';
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

.img-container {
  overflow: hidden;
  margin: 0 auto;
  display: flex;
  justify-content: center;
  align-items: baseline;
  cursor: pointer;
  transition: all .2s;
}
</style>