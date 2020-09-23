<template>
  <ValidationObserver ref="form" v-slot="{ handleSubmit, invalid ,reset}">
    <b-modal :visible="showModal" size="xl" id="user-form" ref="modal" :static="true" :lazy="false"
             :title='modalUser.id ? $t(`Update User "{user}"`, {user: modalUser.username}) : $t(`Create New User`)'
             @hidden="closeModal" @ok.prevent="handleSubmit(onSubmit)" :ok-title="$t('Submit')" :ok-disabled="loading"
             scrollable>
      <b-form @submit.prevent="handleSubmit(onSubmit)" @reset="onReset" novalidate>
        <div v-if="modalUser.id" class="alert"
             :class="{'alert-warning': !userModel.status,'alert-success': userModel.status}">
          <div v-if="!userModel.status" class="mb-0">
            <span style="font-size: 1.3rem">{{ $t('User status is disabled.') }}</span> &nbsp;&nbsp;
            <b-form-checkbox style="display: inline-block; position: relative; top:-5px" v-model="userModel.status"
                             name="check-button" switch size="lg">
              {{ $t('Activate User') }}
            </b-form-checkbox>
          </div>
          <div v-if="userModel.status" class="mb-0">
            <span style="font-size: 0.9rem">{{ $t('User status is active.') }}</span> &nbsp;&nbsp;
            <b-form-checkbox style="display: inline-block; position: relative; top:-1px;font-size: 0.9rem"
                             v-model="userModel.status" name="check-button" switch size="lg">
              {{ $t('Disable User') }}
            </b-form-checkbox>
          </div>
        </div>
        <!--<div class="row">
          <div class="col">
            <input-widget :model="userProfileModel" attribute="first_name"/>
          </div>
          <div class="col">
            <input-widget :model="userProfileModel" attribute="last_name"/>
          </div>
        </div>-->
        <div class="row">
          <div class="col">
            <input-widget :model="userModel" attribute="email"/>
          </div>
          <!--<div class="col">
            <input-widget :model="userProfileModel" attribute="phone"/>
          </div>
          <div class="col">
            <input-widget :model="userProfileModel" attribute="mobile"/>
          </div>
        </div>-->
        </div>
        <b-card header-tag="header" footer-tag="footer" class="form-cards">
          <template v-slot:header>
            <b-form-group>
              <b-button size="sm" type="button" v-on:click="addNewWorkspace" variant="success">
                <i class="fa fa-plus-circle "></i>
                {{ $t('Add workspace') }}
              </b-button>
            </b-form-group>
          </template>
          <div v-for="(uw, index) in userWorkspace" :key="index">
            <div class="row">
              <div class="col col-1">
                <b-form-group>
                  <label class="d-block">&nbsp;</label>
                  <b-button v-b-tooltip :title="$t('Remove workspace')" pill v-on:click="removeWorkspace(index)"
                            variant="outline-danger">
                    <i class="fa fa-times"></i>
                  </b-button>
                </b-form-group>
              </div>
              <!--TODO after workspace-->
              <!--<div class="col col-5">
                <input type="hidden" name="workspace-id" v-model="uw.id">
                <b-form-group :label="$t('Workspace')">
                  <b-form-select v-model="uw.workspace_id"/>
                </b-form-group>
              </div>-->
              <div class="col col-5">
                <b-form-group :label="$t('Role')">
                  <b-form-select v-model="uw.role" :options="workspaceRoles"/>
                </b-form-group>
              </div>
            </div>
          </div>
        </b-card>

        <b-card header-tag="header" footer-tag="footer" class="form-cards">
          <template v-slot:header>
            <b-form-group>
              <b-button size="sm" type="button" v-on:click="addNewPosition" variant="success">
                <i class="fa fa-plus-circle "></i>
                {{ $t('Add Position') }}
              </b-button>
            </b-form-group>
          </template>
          <div class="row">
            <div class="col col-12">
              <div class="mb-3 " v-for="(dp, index) in userDepartment" :key="index">
                <div class="row">
                  <div class="col-sm-1 col-1">
                    <b-form-group>
                      <label class="d-block">&nbsp;</label>
                      <b-button v-b-tooltip :title="$t('Remove position')" pill v-on:click="removePosition(index)"
                                variant="outline-danger">
                        <i class="fa fa-times"></i>
                      </b-button>
                    </b-form-group>
                  </div>
                  <div class="col-11">
                    <div class="row">
                      <div class="col-sm-12 col-md-4">
                        <b-form-group :label="$t('Position')">
                          <b-form-input v-model="dp.position" list="job-title-list"/>
                          <datalist id="job-title-list">
                            <option v-for="position in positionOptions"
                                    v-bind:value="position.value"
                                    v-bind:label="position.text">
                            </option>
                          </datalist>
                        </b-form-group>
                      </div>
                      <div class="col-sm-12 col-md-4">
                        <b-form-group :label="$t('Country')">
                          <b-form-input v-model="dp.country" list="country-list"/>
                          <b-form-datalist id="country-list" :options="autoCompleteData.countryOptions"/>
                        </b-form-group>
                      </div>
                      <div class="col-sm-12 col-md-4">
                        <b-form-group :label="$t('Departments')" id="department-form">
                          <multiselect id="department" v-model="dp.department"
                                       :tag-placeholder="$t('Add department')"
                                       :placeholder="$t('Search or add a department')"
                                       :options="filterOptions(dp.country)" :multiple="true"
                                       :taggable="true" aria-labelledby="feedback-department"
                                       :selectLabel="$t('Press enter to select')"
                                       :deselectLabel="$t('Press enter to remove')"
                                       :selectedLabel="$t('Selected')" track-by="id" label="department_name"
                                       @tag="addDepartment(dp, $event)">
                            <span slot="noOptions">{{ $t('List is empty.') }}</span>
                            <template slot="tag" slot-scope="{ option, remove }">
                                <span class="multiselect__tag">
                                  <span>{{ $t(option.department_name) }}</span>
                                  <span class="multiselect__tag-icon" @click="remove(option)"></span>
                                </span>
                            </template>
                          </multiselect>
                        </b-form-group>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </b-card>
        <b-alert show variant="info">
          <li>
            {{
              $t(`"Executive Board of Corporate Group" does not need further selections as "Country, Departments or Sub-Departments`)
            }}
          </li>
          <li>{{ $t('General Manager" does not require any "Departments"') }}</li>
          <li>{{ $t('Note: Head of Division is the same as Head of Department') }}</li>
        </b-alert>
      </b-form>
    </b-modal>
  </ValidationObserver>
</template>

<script>
import Vue from 'vue';
import {createNamespacedHelpers, mapState as mapStateGlobal} from 'vuex';
import Multiselect from 'vue-multiselect';

import {ValidationProvider, ValidationObserver, configure} from 'vee-validate';

import InputWidget from "../../../core/components/input-widget/InputWidget";
import UserFormModel from "./UserFormModel";

const {mapState, mapActions} = createNamespacedHelpers('user');

export default {
  name: "UserForm",
  components: {Multiselect, ValidationProvider, ValidationObserver, InputWidget},
  data() {
    return {
      userModel: new UserFormModel(),
      userWorkspaceEmpty: {
        id: '',
        role: '',
      },
      userDepartment: [],
      userWorkspace: [],
      workspaceRoles: [
        {'value': 'user', 'text': 'User'},
        {'value': 'admin', 'text': 'Admin'},
        {'value': 'workspaceAdmin', 'text': 'Workspace Admin'}
      ],
      loading: false,
    }
  },
  computed: {
    ...mapState({
      modalUser: state => state.userList.modalUser,
      showModal: state => state.userList.showModal,
      autoCompleteData: state => state.autoCompleteData,
    }),
  },
  watch: {
    modalUser() {
      const u = this.modalUser;
      this.workspaceRoles = [
        {'value': 'portalUser', 'text': 'User'},
        {'value': 'workspaceEditor', 'text': 'Workspace Admin'},
        {'value': 'workspaceAdmin', 'text': 'Admin'}
      ];
      if (u.id) {
        this.userModel.id = u.id;
        this.userModel.email = u.email;
        this.userModel.status = u.status === 1;
        /*this.userProfileModel.first_name = u.userProfile.first_name;
        this.userProfileModel.last_name = u.userProfile.last_name;
        this.userProfileModel.phone = u.userProfile.phone;
        this.userProfileModel.mobile = u.userProfile.mobile;
        this.userProfileModel.birthday = u.userProfile.birthday;
        this.userProfileModel.hobby = u.userProfile.hobby;
        this.userProfileModel.about_me = u.userProfile.about_me;*/
      }
    }
  },
  created() {
    configure({
      defaultMessage: (_, values) => this.$t(`This field is ${values._rule_}`, values)
    });
  },
  methods: {
    ...mapActions(['update', 'hideModal', 'addDepartmentOptions']),
    checkValidity: (touched, validated, valid) => {
      return (!touched && !validated) ? null : valid;
    },
    generate: function () {
      this.userModel.password = Math.random().toString(36).slice(-8);
    },
    filterOptions: function (country) {
      let opt = this.autoCompleteData.departmentOptions;
      opt = opt.filter(i => i.country === country);
      return opt;
    },
    closeModal: function () {
      this.hideModal();
      this.userModel.password = '';
    },
    addNewWorkspace: function () {
      this.userWorkspace.push(Vue.util.extend({}, this.userWorkspaceEmpty))
    },
    removeWorkspace: function (index) {
      Vue.delete(this.userWorkspace, index);
    },
    addNewPosition: function () {
      this.userDepartment.push({
        department: []
      })
    },
    removePosition: function (index) {
      Vue.delete(this.userDepartment, index);
    },
    addDepartment(dp, newDepartment) {
      const tag = {
        value: newDepartment,
        text: newDepartment
      };
      dp.department.push(tag);
      this.addDepartmentOptions(tag);
    },
    addCountry(country) {
      this.userProfileModel.country.push(country);
    },
    async onSubmit(evt) {
      if (evt) {
        evt.preventDefault();
      }
      if (!this.userModel.email) {
        this.userModel.email = Math.floor(Math.random() * 1000000000) + '@agora.intranet.com';
      }
      let res;

      const data = {
        ...this.userModel
      };
      data.status = data.status ? 1 : 2;
      data.password = this.password;

      this.loading = true;
      res = await this.update(data);
      this.loading = false;

      if (res.success) {
        this.$notify({
          group: 'success',
          type: 'success',
          title: this.$t('Success'),
          text: this.$t(`The user "{user}" was successfully updated`, {user: this.userModel.email})
        });
        this.$nextTick(() => {
          this.userModel = new UserFormModel();
        });
      } else {
        this.$notify({
          group: 'error',
          type: 'error',
          title: this.$t('Error'),
          text: this.$t(`The user "{user}" was not updated`, {user: this.userModel.email})
        });
      }
      this.$nextTick(() => {
        this.hideModal();
        this.userModel = new UserFormModel();
      });
    },
    onReset(evt) {
      evt.preventDefault();
      this.userModel = new UserFormModel();
    },
  }
}
</script>

<style lang="scss" scoped>
.ck-editor__editable {
  min-height: 240px;
}

input {
  margin-bottom: 5px;
}

.form-cards {

  margin-bottom: 20px;

  .card-header {
    padding-bottom: 0;
  }
}

.multiselect.is-invalid {
  border: 1px solid #e54d42;
  border-radius: 0.25rem;
  background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
}

.invalid-feedback {
  position: absolute;
  bottom: 0;
  width: 80%;
  display: block;
}

.form-group {
  margin-top: 5px;
}

.multiselect__placeholder {
  margin-bottom: 0;
}

.multiselect__tags {
  padding: 5px 40px 0 8px;
}

.multiselect__select {
  height: 34px;
}

.btn-danger {
  justify-content: center;
  display: flex;
}
</style>

<style src="vue-multiselect/dist/vue-multiselect.min.css"/>
