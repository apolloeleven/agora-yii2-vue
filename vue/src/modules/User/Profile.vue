<template>
  <div class="user-profile-wrapper page">
    <div class="page-content p-3">
      <content-spinner :show="currentUser.loading" :text="$t('Please wait...')" class="h-100"/>
      <ValidationObserver ref="loginForm" tag="div">
        <form v-if="!currentUser.loading && currentUser.loaded" v-on:submit.prevent="onUpdateClick">
          <div class="row">
            <div class="col-md-12 col-lg-7 mb-3">
              <div class="card card-information">
                <div class="card-header">
                  <h5 class="m-0">{{ $t('Personal Details') }}</h5>
                </div>
                <div class="card-body">
                  <div class="mb-3 d-flex justify-content-center">
                    <ImageUploader v-model="image" :src="currentUser.data.image_url" ref="uploader"/>
                  </div>
                  <div class="row">
                    <div class="col-sm-12 col-md-6">
                      <input-widget :model="userModel" attribute="first_name" type="text"/>
                    </div>
                    <div class="col-sm-12 col-md-6">
                      <input-widget :model="userModel" attribute="last_name" type="text"/>
                    </div>
                  </div>
                  <input-widget :model="userModel" attribute="email" type="email"/>
                  <input-widget :model="userModel" attribute="mobile" type="text"/>
                  <input-widget :model="userModel" attribute="phone" type="text"/>
                  <input-widget :model="userModel" attribute="birthday" type="date"/>
                  <input-widget :model="userModel" attribute="hobbies" type="tags"/>
                  <div class="row">
                    <div class="col-md-6">
                      <!--                          <input-widget v-if="userModel" :model="userModel" attribute="about_me" type="richtext"/>-->
                    </div>
                  </div>
                  <div class="text-right">
                    <b-button variant="info" @click="onUpdateClick">
                      {{ $t('Update') }}
                    </b-button>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-12 col-lg-5">
              <div class="card card-account">
                <div class="card-header">
                  <h5 class="m-0">{{ $t('Change account password') }}</h5>
                </div>
                <div class="card-body">

                  <input-widget :model="userModel" attribute="old_password" type="password" vid="password"/>
                  <input-widget :model="userModel" attribute="password" type="password" vid="password"/>
                  <input-widget :model="userModel" attribute="confirm_password" type="password"
                                vid="confirm_password"/>

                  <div class="text-right">
                    <b-button variant="primary">{{ $t('Change Password') }}</b-button>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!--          This button is necessary in order submit on enter on form to work-->
          <button style="display: none" type="submit"></button>
        </form>
      </ValidationObserver>
    </div>
    <!--    <page-footer>-->
    <!--      <b-button variant="info" @click="onUpdateClick">-->
    <!--        {{ $t('Update') }}-->
    <!--      </b-button>-->
    <!--    </page-footer>-->
  </div>
</template>

<script>
import {createNamespacedHelpers} from 'vuex';
import UserModel from "@/modules/User/UserModel";
import InputWidget from "@/core/components/input-widget/InputWidget";
import ContentSpinner from "@/core/components/ContentSpinner";
import ImageUploader from "@/core/components/ImageUploader";
import PageHeader from "@/core/components/PageHeader";
import PageFooter from "@/core/components/PageFooter";

const {mapState, mapActions} = createNamespacedHelpers('user');
export default {
  name: "Profile",
  components: {PageFooter, PageHeader, InputWidget, ContentSpinner, ImageUploader},
  data() {
    return {
      userModel: new UserModel(),
      image: null,
    }
  },
  computed: {
    ...mapState({
      currentUser: state => state.currentUser,
      currentUserData: state => state.currentUser.data
    }),
  },
  watch: {
    currentUserData() {
      this.initModel()
    }
  },
  methods: {
    ...mapActions(['getProfile', 'updateProfile']),
    onUpdateClick() {
      this.userModel.resetErrors();
      this.userModel.image = this.image;
      const userModel = JSON.parse(JSON.stringify(this.userModel))
      userModel.image = this.image;
      this.updateProfile(userModel);
    },
    async initModel() {
      // We need to set timeout, because the data from backend comes faster then the CkEditor rendered.
      // TODO Need optimization!
      setTimeout(() => {
        const userData = JSON.parse(JSON.stringify(this.currentUser.data));
        this.userModel = new UserModel(userData);
        // console.log(this.userModel);

      }, 500);
    }
  },
  mounted() {
    this.initModel()
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