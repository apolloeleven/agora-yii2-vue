import {HIDE_COUNTRY_MODAL, SET_COUNTRIES, SET_COUNTRIES_LOADING, SHOW_COUNTRY_MODAL} from './mutation-types';
import httpService from "@/core/services/httpService";
import {
  HIDE_DEPARTMENT_MODAL,
  SET_DEPARTMENTS,
  SHOW_DEPARTMENT_MODAL
} from "@/store/setup/mutation-types";

/**
 *
 * @param { function } commit
 */
export async function getCountries({commit}) {
  commit(SET_COUNTRIES_LOADING, true);
  // Make request to get countries
  const {success, body} = await httpService.get('/v1/setup/countries', {
    params: {
      expand: 'createdBy,departments',
      sort: 'name'
    }
  })
  if (success) {
    commit(SET_COUNTRIES, {countries: body});
  }
  commit(SET_COUNTRIES_LOADING, false)
}

export function showCountryModal({commit}, country) {
  commit(SHOW_COUNTRY_MODAL, country)
}

export function hideCountryModal({commit}) {
  commit(HIDE_COUNTRY_MODAL)
}

export async function saveCountry({dispatch}, country) {
  let response;
  if (!country.id) {
    response = await httpService.post(`/v1/setup/countries`, country)
  } else {
    response = await httpService.put(`/v1/setup/countries/${country.id}`, country)
  }
  if (response.success) {
    dispatch('getCountries')
  }

  return response;
}

export async function deleteCountry({dispatch}, id) {
  let response = await httpService.delete(`/v1/setup/countries/${id}`)
  if (response.success) {
    dispatch('getCountries')
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

export async function saveDepartment({}, department) {
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