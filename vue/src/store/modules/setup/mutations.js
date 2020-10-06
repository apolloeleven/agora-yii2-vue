import {
  CREATE_COUNTRY,
  HIDE_COUNTRY_MODAL,
  SET_COUNTRIES,
  SET_COUNTRIES_LOADING, SET_INVITATIONS,
  SHOW_COUNTRY_MODAL, UPDATE_COUNTRY
} from './mutation-types';
import {
  HIDE_DEPARTMENT_MODAL, HIDE_INVITATION_MODAL,
  SET_DEPARTMENTS, SET_INVITATIONS_LOADING,
  SHOW_DEPARTMENT_MODAL,
  SHOW_INVITATION_MODAL
} from "@/store/modules/setup/mutation-types";

export default {
  [SET_COUNTRIES_LOADING](state, loading) {
    state.countries.loading = loading;
  },
  [SET_COUNTRIES](state, {countries}) {
    state.countries.loaded = true;
    state.countries.data = countries.map(country => {
      country.departmentsTree = getTree(country.departments, null);
      return country;
    });
  },
  [SHOW_COUNTRY_MODAL](state, country) {
    state.countryModal.show = true;
    state.countryModal.data = country;
  },
  [HIDE_COUNTRY_MODAL](state) {
    state.countryModal.show = false;
    state.countryModal.data = {};
  },
  [UPDATE_COUNTRY](state, payload) {
    const index = state.countries.data.findIndex(c => c.id === payload.id);
    state.countries.data[index] = {...state.countries.data[index], ...payload};
    state.countries.data[index].departmentsTree = getTree(state.countries.data[index].departments, null)
    state.countries.data = [...state.countries.data];
  },
  [CREATE_COUNTRY](state, country) {
    country.departmentsTree = getTree(country.departments, null);
    state.countries.data.push(country);
  },
  [SET_DEPARTMENTS](state, {departments, countryId}) {
    const country = state.countries.data.find(country => country.id === countryId);
    country.departments = departments;
    country.departmentsTree = getTree(country.departments, null);
  },
  [SHOW_DEPARTMENT_MODAL](state, department) {
    state.departmentModal.show = true;
    state.departmentModal.data = department;
  },
  [HIDE_DEPARTMENT_MODAL](state) {
    state.departmentModal.show = false;
    state.departmentModal.data = {};
  },
  [SET_INVITATIONS_LOADING](state, loading) {
    state.invitations.loading = loading
  },
  [SET_INVITATIONS](state, invitations) {
    state.invitations.loaded = true;
    state.invitations.data = invitations;
  },
  [SHOW_INVITATION_MODAL]: (state) => {
    state.invitationModal.show = true;
    state.invitationModal.data = {};
  },
  [HIDE_INVITATION_MODAL]: (state) => {
    state.invitationModal.show = false;
    state.invitationModal.data = {};
  },
};

const getTree = (departments, parentId = null) => {
  const nodes = [];
  for (const item of departments) {
    if (item.parent_id === parentId) {
      item.children = getTree(departments, item.id);
      nodes.push(item);
    }
  }

  return nodes
}
