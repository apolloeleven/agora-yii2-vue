<template>
  <ValidationObserver ref="form" v-slot="{ handleSubmit, invalid ,reset}">
    <b-modal
        :visible="showModal" id="user-form" ref="modal" :title='$t(`Edit employee`)' @hidden="onHideModal" size="lg"
        @ok.prevent="handleSubmit(onSubmit)" :ok-disabled="loading" :ok-title="$t('Submit')" scrollable>
      <content-spinner :show="loading" :text="$t('Please wait...')" :fullscreen="true" class="h-100"/>
      <b-form @submit.prevent="handleSubmit(onSubmit)" novalidate>
        <div class="row">
          <div class="col-md-12">
            <input-widget :model="model" attribute="email"/>
          </div>
          <div class="col-md-6">
            <input-widget :model="model" attribute="first_name"/>
          </div>
          <div class="col-md-6">
            <input-widget :model="model" attribute="last_name"/>
          </div>
        </div>


        <b-card header-tag="header" footer-tag="footer" class="form-cards mb-3" body-class="pb-0">
          <template v-slot:header>
            <div class="d-flex align-items-center">
              <h5 class="mb-0">{{ $t('Roles') }}</h5>
              <b-button size="sm" type="button" v-on:click="addNewRole" variant="success" class="ml-auto">
                <i class="fa fa-plus-circle "></i>
                {{ $t('Add New') }}
              </b-button>
            </div>
          </template>
          <div class="row">
            <div class="col col-12">
              <div class="mb-3 " v-for="(userRoleModel, index) in model.roles" :key="index">
                <div class="row">
                  <div class="col-sm-1 col-1 d-flex align-items-center">
                    <b-button v-b-tooltip :title="$t('Remove role')" pill v-on:click="removeRole(index)"
                              variant="outline-danger" size="sm">
                      <i class="fa fa-times"></i>
                    </b-button>
                  </div>
                  <div class="col-11">
                    <div class="row">
                      <div class="col-sm-12 col-md-6">
                        <b-form-group class="mb-0">
                          <input-widget :model="userRoleModel" attribute="name" type="select" :select-options="dropdownData.userRoles"/>
                        </b-form-group>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </b-card>

        <b-card header-tag="header" footer-tag="footer" class="form-cards" body-class="pb-0">
          <template v-slot:header>
            <div class="d-flex align-items-center">
              <h5 class="mb-0">{{ $t('Positions') }}</h5>
              <b-button size="sm" type="button" v-on:click="addUserDepartment" variant="success" class="ml-auto">
                <i class="fa fa-plus-circle "></i>
                {{ $t('Add New') }}
              </b-button>
            </div>
          </template>
          <div class="row">
            <div class="col col-12">
              <div class="mb-3 " v-for="(userDepartmentModel, index) in model.userDepartments" :key="index">
                <div class="row">
                  <div class="col-sm-1 col-1 d-flex align-items-center">
                    <b-button v-b-tooltip :title="$t('Remove position')" pill v-on:click="removeUserDepartment(index)"
                              variant="outline-danger" size="sm">
                      <i class="fa fa-times"></i>
                    </b-button>
                  </div>
                  <div class="col-11">
                    <div class="row">
                      <div class="col-sm-12 col-md-4">
                        <b-form-group class="mb-0">
                          <input-widget :model="userDepartmentModel"
                                        attribute="country_id"
                                        type="select"
                                        :select-options="dropdownData.countries"/>
                        </b-form-group>
                      </div>
                      <div class="col-sm-12 col-md-4">
                        <b-form-group class="mb-0">
                          <input-widget :model="userDepartmentModel"
                                        attribute="department_id"
                                        type="select"
                                        :select-options="getDepartments(userDepartmentModel)"/>
                        </b-form-group>
                      </div>
                      <div class="col-sm-12 col-md-4">
                        <b-form-group class="mb-0">
                          <input-widget :model="userDepartmentModel" attribute="position"/>
                        </b-form-group>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </b-card>

      </b-form>
    </b-modal>
  </ValidationObserver>
</template>

<script>

import {createNamespacedHelpers} from "vuex";
import ContentSpinner from "../../../core/components/ContentSpinner";
import InputWidget from "../../../core/components/input-widget/InputWidget";
import EmployeeModel from "./EmployeeModel.js";
import Vue from "vue"
import RoleModel from "@/modules/User/Employees/RoleModel";
import employeeService from "@/modules/User/Employees/employeesService";
import UserDepartmentModel from "@/modules/User/Employees/UserDepartmentModel";

const {mapState, mapActions} = createNamespacedHelpers('user/employees');

export default {
  name: "EmployeeFormModal",
  components: {ContentSpinner, InputWidget},
  data() {
    return {
      loading: false,
      model: new EmployeeModel(),
    }
  },
  computed: {
    ...mapState({
      showModal: state => state.modal.show,
      object: state => state.modal.object,
      dropdownData: state => state.modalDropdownData
    })
  },
  watch: {
    object() {
      this.model = new EmployeeModel(this.object);
    }
  },
  methods: {
    ...mapActions(['hideModal', 'getData']),
    getDepartments(userDepartmentModel) {
      if (!userDepartmentModel.country_id) {
        return [];
      }

      const country = this.dropdownData.countries.find(c => c.id === userDepartmentModel.country_id);

      if (!country) {
        return [];
      }

      return country.departments;
    },
    onHideModal() {
      this.model = new EmployeeModel();
      this.hideModal();
    },
    addUserDepartment: function () {
      this.model.userDepartments.push(new UserDepartmentModel());
    },
    removeUserDepartment: function (index) {
      Vue.delete(this.model.userDepartments, index);
    },
    addNewRole: function () {
      this.model.roles.push(new RoleModel());
    },
    removeRole: function (index) {
      Vue.delete(this.model.roles, index);
    },
    async onSubmit() {
      const {success, body} = await employeeService.updateUserData(this.model);
      if (success) {
        this.hideModal();
        await this.getData();
      } else {

      }
    }
  },
  mounted() {
  }
}
</script>

<style scoped>

</style>