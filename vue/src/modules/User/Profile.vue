<template>
  <div class="user-profile-wrapper page">
    <div class="page-content p-3">
      <div class="row">
        <div class="col-md-12 col-lg-7 mb-3">
          <ValidationObserver ref="profileForm" tag="div" v-slot="{invalid}">
            <form v-if="currentUser.loaded" v-on:submit.prevent="onUpdateClick">
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
                    <b-button variant="info" @click="onUpdateClick"  :disabled="invalid || currentUser.loading">
                      <b-spinner v-if="currentUser.loading" small></b-spinner>
                      {{ $t('Update') }}
                    </b-button>
                  </div>
                </div>
              </div>

              <!--          This button is necessary in order submit on enter on form to work-->
              <button style="display: none" type="submit"></button>
            </form>
          </ValidationObserver>
        </div>
        <div class="col-md-12 col-lg-5">
          <ValidationObserver ref="changePasswordForm" tag="div" v-slot="{invalid}">
            <form v-on:submit.prevent="onUpdateClick">
              <div class="card card-account">
                <div class="card-header">
                  <h5 class="m-0">{{ $t('Change account password') }}</h5>
                </div>
                <div class="card-body">

                  <input-widget :model="passwordResetModel" attribute="old_password" type="password"/>
                  <input-widget :model="passwordResetModel" attribute="password" type="password"/>
                  <input-widget :model="passwordResetModel" attribute="confirm_password" type="password"/>

                  <div class="text-right">
                    <b-button variant="primary" @click="onPasswordChange()" :disabled="invalid || passwordForm.loading">
                      <b-spinner v-if="passwordForm.loading" small></b-spinner>
                      {{ $t('Change Password') }}
                    </b-button>
                  </div>
                </div>
              </div>
            </form>
          </ValidationObserver>
        </div>
      </div>
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
import PasswordResetModel from "@/modules/User/PasswordResetModel";
import i18n from "@/shared/i18n";

const {mapState, mapActions} = createNamespacedHelpers('user');
export default {
  name: "Profile",
  components: {InputWidget, ContentSpinner, ImageUploader},
  data() {
    return {
      userModel: new UserModel(),
      image: null,
      passwordResetModel: new PasswordResetModel(),
    }
  },
  computed: {
    ...mapState({
      currentUser: state => state.currentUser,
      currentUserData: state => state.currentUser.data,
      passwordForm: state => state.passwordForm
    }),
  },
  watch: {
    currentUserData() {
      this.initModel()
    }
  },
  methods: {
    ...mapActions(['getProfile', 'updateProfile', 'changePassword']),
    async onUpdateClick() {
      this.userModel.resetErrors();
      this.userModel.image = this.image;
      this.userModel.imageRemoved = this.$refs.uploader.imageRemoved;
      const userModel = JSON.parse(JSON.stringify(this.userModel))
      userModel.image = this.image;
      const {success} = await this.updateProfile(userModel);
      if (success) {
        this.$successToast(i18n.t('Your profile was updated'))
        this.$refs.profileForm.reset()
      }
    },
    async initModel() {
      // We need to set timeout, because the data from backend comes faster then the CkEditor rendered.
      // TODO Need optimization!
      setTimeout(() => {
        const userData = JSON.parse(JSON.stringify(this.currentUser.data));
        this.userModel = new UserModel(userData);
        // console.log(this.userModel);

      }, 500);
    },
    async onPasswordChange() {
      this.passwordResetModel.resetErrors();
      const {success, body} = await this.changePassword(this.passwordResetModel)
      if (!success) {
        this.passwordResetModel.setMultipleErrors(body);
      } else {
        this.$successToast(i18n.t('Your password was updated'))
        this.passwordResetModel = new PasswordResetModel();
        this.$refs.changePasswordForm.reset()
      }
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