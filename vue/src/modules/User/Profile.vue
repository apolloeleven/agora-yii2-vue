<template>
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
            <b-input ref="email" :name='`email`' :key='`email`' v-model="user.name" class="mr-sm-2"
                     placeholder="Email"/>
          </div>
          <div class="col-md-6">
            <b-input ref="password"
                     :name='`password`'
                     :key='`password`'
                     v-model="user.password" class="mr-sm-2"
                     placeholder="Password"/>
          </div>
          <div class="col-md-6">
            <b-input ref="confirmPassword"
                     :name='`confirmPassword`'
                     :key='`confirmPassword`'
                     v-model="user.confirmPassword" class="mr-sm-2"
                     placeholder="Confirm Password"/>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="p-3 ">
        <h4 class="text-left">Profile Settings</h4>
        <div class="row mt-4">
          <div class="col-md-6 mb-4">
            <b-input ref="name"
                     :name='`name`'
                     :key='`name`'
                     v-model="user.name" class="mr-sm-2"
                     placeholder="First Name"/>
          </div>
          <div class="col-md-6">
            <b-input ref="lastName"
                     :name='`lastName`'
                     :key='`lastName`'
                     v-model="user.lastName" class="mr-sm-2"
                     placeholder="Last Name"/>
          </div>
          <div class="col-md-6 mb-4">
            <b-input ref="phone"
                     :name='`phone`'
                     :key='`phone`'
                     v-model="user.phone" class="mr-sm-2"
                     placeholder="Phone"/>
          </div>
          <div class="col-md-6">
            <b-input ref="mobile"
                     :name='`mobile`'
                     :key='`mobile`'
                     v-model="user.mobile" class="mr-sm-2"
                     placeholder="Mobile"/>
          </div>
          <div class="col-md-12 mb-4"><label class="labels">Birthday</label>
            <b-input ref="birthday"
                     :type="`date`"
                     :name='`birthday`'
                     :key='`birthday`'
                     v-model="user.birthday" class="mr-sm-2"
                     placeholder="Birthday"/>
          </div>
          <div class="col-md-12 mb-4">
            <label class="labels">About Me:</label>
            <b-textarea ref="about"
                        :name='`about`'
                        :key='`about`'
                        v-model="user.about" class="mr-sm-2"
                        placeholder="About"></b-textarea>
          </div>
          <div class="col-md-12 mb-4 d-flex justify-content-end">
            <b-button class="btn btn-outline-success" @click="">Update</b-button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  import {createNamespacedHelpers} from 'vuex';

  const {mapState, mapActions} = createNamespacedHelpers('user');
  export default {
    name: "Profile",
    data() {
      return {}
    },
    computed: {
      ...mapState(['user']),
    },
    methods: {
      ...mapActions(['setUser','updateUser']),
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