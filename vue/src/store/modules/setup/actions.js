import {
  CREATE_COUNTRY,
  HIDE_COUNTRY_MODAL,
  SET_COUNTRIES,
  SET_COUNTRIES_LOADING,
  SHOW_COUNTRY_MODAL, UPDATE_COUNTRY
} from './mutation-types';
import httpService from "@/core/services/httpService";
import {
  HIDE_DEPARTMENT_MODAL,
  SET_DEPARTMENTS,
  SHOW_DEPARTMENT_MODAL,
  SET_INVITATIONS_LOADING,
  SET_INVITATIONS,
  SHOW_INVITATION_MODAL, HIDE_INVITATION_MODAL
} from "@/store/modules/setup/mutation-types";

/**
 *
 * @param { function } commit
 */
export async function getCountries({commit}) {
  commit(SET_COUNTRIES_LOADING, true);
  // Make request to get countries
  const response = await httpService.get('/v1/setup/countries', {
    params: {
      expand: 'createdBy,departments',
      sort: 'name'
    }
  })
  if (response.success) {
    commit(SET_COUNTRIES, {countries: response.body});
  }
  commit(SET_COUNTRIES_LOADING, false);
  return response;
}

export function showCountryModal({commit}, country) {
  commit(SHOW_COUNTRY_MODAL, country)
}

export function hideCountryModal({commit}) {
  commit(HIDE_COUNTRY_MODAL)
}

export async function updateCountry({commit}, country) {
  let response = await httpService.put(`/v1/setup/countries/${country.id}`, country, {
    params: {expand: 'createdBy,departments',}
  })

  if (response.success) {
    commit(UPDATE_COUNTRY, response.body)
  }
  return response;
}

export async function createCountry({commit}, country) {
  let response = await httpService.post(`/v1/setup/countries`, country, {
    params: {expand: 'createdBy,departments'}
  })

  if (response.success) {
    commit(CREATE_COUNTRY, response.body)
  }
  return response;
}

export async function deleteCountry({commit, state}, id) {
  let response = await httpService.delete(`/v1/setup/countries/${id}`);

  if (response.success) {
    commit(SET_COUNTRIES, {countries: state.countries.data.filter(country => country.id !== id)});
  }

  return response;
}

export async function getCountryDepartments({commit}, countryId) {
  const {success, body} = await httpService.get(`/v1/setup/departments?country_id=${countryId}`, {
    params: {
      sort: 'name'
    }
  })
  if (success) {
    commit(SET_DEPARTMENTS, {departments: body, countryId});
  }
}

export function showDepartmentModal({commit}, department) {
  commit(SHOW_DEPARTMENT_MODAL, department)
}

export function hideDepartmentModal({commit}) {
  commit(HIDE_DEPARTMENT_MODAL)
}

export async function saveDepartment({commit}, department) {
  let response;
  if (!department.id) {
    response = await httpService.post(`/v1/setup/departments`, department)
  } else {
    response = await httpService.put(`/v1/setup/departments/${department.id}`, department)
  }

  return response;
}

export async function deleteDepartment({dispatch}, id) {
  let response = await httpService.delete(`/v1/setup/departments/${id}`)

  return response;
}

export async function getInvitations({commit}) {
  commit(SET_INVITATIONS_LOADING);
  const res = await httpService.get(`/v1/setup/invitation?expand=createdBy,user.userDepartments.department.country&sort=-created_at`);
  commit(SET_INVITATIONS_LOADING);
  commit(SET_INVITATIONS, res.body);
}

export function showInvitationModal({commit}) {
  commit(SHOW_INVITATION_MODAL);
}

export function hideInvitationModal({commit}) {
  commit(HIDE_INVITATION_MODAL);
}

export async function inviteUser({dispatch}, email) {
  const res = await httpService.post(`/v1/setup/invitation`, {email});
  if (res.success) {
    dispatch('getInvitations');
  }
  return res;
}

export async function deleteInvitation({dispatch}, data) {
  const res = await httpService.delete(`/v1/setup/invitation/${data.id}`);
  if (res.success) {
    dispatch('getInvitations');
  }
  return res;
}

