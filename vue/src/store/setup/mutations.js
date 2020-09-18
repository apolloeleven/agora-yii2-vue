import {HIDE_COUNTRY_MODAL, SET_COUNTRIES, SET_COUNTRIES_LOADING, SHOW_COUNTRY_MODAL} from './mutation-types';
import {
  HIDE_DEPARTMENT_MODAL,
  SET_DEPARTMENTS,
  SET_DEPARTMENTS_LOADING,
  SHOW_DEPARTMENT_MODAL
} from "@/store/setup/mutation-types";

export default {
  [SET_COUNTRIES_LOADING](state, loading) {
    state.countries.loading = loading;
  },
  /**
   *
   * @param { object } state
   * @param { array } countries
   */
  [SET_COUNTRIES](state, {countries}) {
    state.countries.loaded = true;
    state.countries.data = countries;
  },
  [SHOW_COUNTRY_MODAL](state, country) {
    state.countryModal.show = true;
    state.countryModal.data = country;
  },
  [HIDE_COUNTRY_MODAL](state) {
    state.countryModal.show = false;
  },
  [SET_DEPARTMENTS_LOADING](state, loading) {
    state.departments.loading = loading;
  },
  [SET_DEPARTMENTS](state, {departments}) {
    state.departments.loaded = true;
    state.departments.data = departments;
  },
  [SHOW_DEPARTMENT_MODAL](state, department) {
    state.departmentModal.show = true;
    state.departmentModal.data = department;
  },
  [HIDE_DEPARTMENT_MODAL](state) {
    state.departmentModal.show = false;
  }
};
