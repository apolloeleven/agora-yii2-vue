<template>
  <ValidationObserver ref="form" v-slot="{ handleSubmit, invalid ,reset}">
    <b-modal
      :visible="showModal"
      id="user-form"
      ref="modal"
      :title='$t(`Edit employee "{user}"`, {user: object.email})'
      @hidden="onHideModal"
      size="lg"
      @ok.prevent="handleSubmit(onSubmit)"
      :ok-disabled="loading"
      :ok-title="$t('Submit')" scrollable>
      <content-spinner :show="loading" :text="$t('Please wait...')" :fullscreen="true" class="h-100"/>
      <b-form @submit.prevent="handleSubmit(onSubmit)" novalidate>
        <div class="row">
          <div class="col-md-12">
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
              <div class="col-md-6">
                <input-widget :model="model" attribute="mobile"/>
              </div>
              <div class="col-md-6">
                <input-widget :model="model" attribute="phone"/>
              </div>
              <div class="col-md-6">
                <input-widget :model="model" attribute="birthday" type="date"/>
              </div>
              <div class="col-md-6">
                <label>User Status</label>
                <input-widget
                  :class="textColor" size="lg" type="checkbox" :model="model" attribute="status" :is-switch="true">
                </input-widget>
              </div>
              <div class="col-md-12">
                <input-widget :model="model" attribute="hobbies" type="tags"/>
              </div>
            </div>
          </div>
<!--          <div class="col-md-5">-->
<!--            <h4 class="pb-2 border-bottom">{{ $t('User Workspaces') }}</h4>-->
<!--            <div class="mb-3 " v-for="(userRoleModel, index) in model.userWorkspaces" :key="`user-role-${index}`">-->
<!--              <pre>{{ userRoleModel }}</pre>-->
<!--            </div>-->
<!--            <no-data :model="model.userWorkspaces" :loading="loading" :height="100"-->
<!--                   :text="$t('User is not part of any workspaces')"></no-data>-->
<!--          </div>-->
        </div>

        <b-card class="form-cards mb-3">
          <template v-slot:header>
            <div class="d-flex align-items-center">
              <h5 class="mb-0">{{ $t('Workspaces') }}</h5>
            </div>
          </template>
          <div class="mb-3">
            <table v-if="model.userWorkspaces.length" class="table table-sm">
              <thead>
              <tr>
                <th>{{$t('Workspace')}}</th>
                <th>{{$t('Role')}}</th>
              </tr>
              </thead>
              <tbody>
              <tr v-for="(userWorkspace, index) in model.userWorkspaces" :key="index">
                <td>{{getWorkspaceName(userWorkspace.workspace_id)}}</td>
                <td>{{getRoleName(userWorkspace.role)}}</td>
              </tr>
              </tbody>
            </table>
          </div>
          <no-data :model="model.userWorkspaces" :loading="loading" :height="100"
                   :text="$t('User is not part of any workspace')"></no-data>
        </b-card>

        <b-card class="form-cards">
          <template v-slot:header>
            <div class="d-flex align-items-center">
              <h5 class="mb-0">{{ $t('Departments/Positions') }}</h5>
              <b-button size="sm" type="button" v-on:click="addUserDepartment" variant="success" class="ml-auto">
                <i class="fa fa-plus-circle "></i>
                {{ $t('Add New') }}
              </b-button>
            </div>
          </template>
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
                    <input-widget :model="userDepartmentModel"
                                  attribute="country_id"
                                  type="select"
                                  value-field="id"
                                  text-field="name"
                                  :select-options="dropdownData.countries"/>
                  </div>
                  <div class="col-sm-12 col-md-4">
                    <input-widget :model="userDepartmentModel"
                                  attribute="department_id"
                                  type="select"
                                  value-field="id"
                                  text-field="name"
                                  :select-options="getDepartments(userDepartmentModel)"/>
                  </div>
                  <div class="col-sm-12 col-md-4">
                    <input-widget :model="userDepartmentModel" attribute="position"/>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <no-data :model="model.userDepartments" :loading="loading" :height="100"
                   :text="$t('User is not part of any department')"></no-data>
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
import employeeService from "@/modules/setup/employees/employeesService";
import UserDepartmentModel from "@/modules/setup/employees/UserDepartmentModel";
import {clone} from "lodash";
import {ACTIVE_USER, INACTIVE_USER} from "../../../constants";
import NoData from "../../../core/components/NoData";

const {mapState, mapActions} = createNamespacedHelpers('employee');
const {mapActions: mapInvitationActions} = createNamespacedHelpers('setup');
const {mapState: mapStateWorkspace} = createNamespacedHelpers('workspace');

export default {
  name: "EmployeeFormModal",
  components: {NoData, ContentSpinner, InputWidget},
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
    }),
    ...mapStateWorkspace(['workspaces']),
    textColor() {
      if (this.model.status) {
        return 'text-success'
      }
      return 'text-danger'
    },
  },
  watch: {
    object() {
      this.model = new EmployeeModel(this.object);
    }
  },
  methods: {
    ...mapActions(['hideModal', 'getData']),
    ...mapInvitationActions(['getInvitations']),
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
    getWorkspaceName(workspaceId) {
      const workspace = this.workspaces.find(w => w.id == workspaceId);
      return workspace ? workspace.name : null;
    },
    getRoleName(roleKey) {
      const role = this.dropdownData.userRoles.find(r => r.value == roleKey);
      return role ? role.text : null;
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
    async onSubmit() {
      let data = clone(this.model);
      let userDepartmentsData = data['userDepartments'];
      delete data['userDepartments'];
      data['userDepartmentsData'] = userDepartmentsData;
      data.status = data.status ? ACTIVE_USER : INACTIVE_USER;

      const {success, body} = await employeeService.updateUserData(data);
      if (success) {
        this.hideModal();
        await this.getData();
        await this.getInvitations();
      } else {
        if (body.message) {
          this.$alert(body.message);
        } else {
          this.model.setMultipleErrors(body)
        }
      }
    }
  }
}
</script>

<style lang="scss">

</style>