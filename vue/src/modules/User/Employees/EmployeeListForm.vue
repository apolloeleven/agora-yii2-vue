<template>
  <ValidationObserver ref="form" v-slot="{ handleSubmit, invalid ,reset}">
    <b-modal
        :visible="showModal" id="user-form" ref="modal" :title='$t(`New Invitation`)' @hidden="onHideModal"
        @ok.prevent="handleSubmit(onSubmit)" :ok-disabled="loading" :ok-title="$t('Submit')" scrollable>
      <content-spinner :show="loading" :text="$t('Please wait...')" :fullscreen="true" class="h-100"/>
      <b-form @submit.prevent="handleSubmit(onSubmit)" novalidate>
        <input-widget :model="model" attribute="firstName"/>
        <input-widget :model="model" attribute="lastName"/>
        <input-widget :model="model" attribute="email"/>
        <b-card header-tag="header" footer-tag="footer" class="form-cards">
          <template v-slot:header>
            <b-form-group>
              <b-button size="sm" type="button" v-on:click="addNewRole" variant="success">
                <i class="fa fa-plus-circle "></i>
                {{ $t('Add Role') }}
              </b-button>
            </b-form-group>
          </template>
          <div v-for="(uw, index) in userRole" :key="index">
            <div class="row">
              <div class="col col-1">
                <b-form-group>
                  <label class="d-block">&nbsp;</label>
                  <b-button v-b-tooltip :title="$t('Remove role')" pill v-on:click="removeRole(index)"
                            variant="outline-danger">
                    <i class="fa fa-times"></i>
                  </b-button>
                </b-form-group>
              </div>
              <div class="col col-5">
                <b-form-group :label="$t('Role')">
                  <b-form-select v-model="uw.role" :options="dropdownData.userRoles"/>
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
                            <option v-for="position in dropdownData.userPositions"
                                    v-bind:value="position.value"
                                    v-bind:label="position.text">
                            </option>
                          </datalist>
                        </b-form-group>
                      </div>
                      <div class="col-sm-12 col-md-4">
                        <b-form-group :label="$t('Country')">
                          <b-form-input v-model="dp.country" list="country-list"/>
                          <b-form-datalist id="country-list" :options="dropdownData.countries"/>
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
                                  <span>{{ $t(option.name) }}</span>
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
      </b-form>
    </b-modal>
  </ValidationObserver>
</template>

<script>

import {createNamespacedHelpers} from "vuex";
import ContentSpinner from "../../../core/components/ContentSpinner";
import InputWidget from "../../../core/components/input-widget/InputWidget";
import EmployeeListFormModel from "./EmployeeListFormModel";
import Multiselect from "vue-multiselect";

const {mapState, mapActions} = createNamespacedHelpers('user/employees');

export default {
  name: "EmployeeListForm",
  components: {ContentSpinner, InputWidget, Multiselect},
  data() {
    return {
      loading: false,
      model: new EmployeeListFormModel(),
      userRole: [],
      userDepartment: []
    }
  },
  computed: {
    ...mapState({
      showModal: state => state.showModal,
      employeeData: state => state.modalEmployee,
      dropdownData: state => state.modalDropdownData
    })
  },
  watch: {
    employeeData() {
      this.model = new EmployeeListFormModel(this.employeeData.email, this.employeeData.firstName, this.employeeData.lastName);
    }
  },
  methods: {
    onHideModal() {
      this.model.email = null;
      this.model.email = null;
      this.hideModal();
    },
    addDepartment(dp, newDepartment) {
      const tag = {
        value: newDepartment,
        text: newDepartment
      };
      dp.department.push(tag);
      this.addDepartmentOptions(tag);
    },
    addNewPosition: function () {
      this.userDepartment.push({
        department: []
      })
    },

    addNewRole: function () {
      this.userRole.push(Vue.util.extend({}, this.userRole))
    },
    removeRole: function (index) {
      Vue.delete(this.userRole, index);
    },
    filterOptions: function (country) {
      let opt = this.dropdownData.departments;
      opt = opt.filter(i => i.country === country);
      return opt;
    },
    async onSubmit() {

    }
  }
}
</script>

<style scoped>

</style>