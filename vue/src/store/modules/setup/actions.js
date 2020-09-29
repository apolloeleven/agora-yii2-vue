import {HIDE_COUNTRY_MODAL, SET_COUNTRIES, SET_COUNTRIES_LOADING, SHOW_COUNTRY_MODAL} from './mutation-types';
import httpService from "@/core/services/httpService";
import {
  HIDE_DEPARTMENT_MODAL,
  SET_DEPARTMENTS,
  SET_DEPARTMENTS_LOADING,
  SHOW_DEPARTMENT_MODAL
} from "@/store/modules/setup/mutation-types";

/**
 *
 * @param { function } commit
 */
export async function getCountries({commit}) {
  commit(SET_COUNTRIES_LOADING, true);
  // Make request to get countries
  const {success, body} = await httpService.get('/v1/setup/countries', {
    params: {
      expand: 'createdBy',
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


/**
 *
 * @param { function } commit
 */
export async function getDepartments({commit}) {
  commit(SET_DEPARTMENTS_LOADING, true);
  // Make request to get departments
  const {success, body} = await httpService.get('/v1/setup/departments', {
    params: {
      expand: 'country,createdBy,parent',
      sort: 'name'
    }
  })
  if (success) {
    commit(SET_DEPARTMENTS, {departments: body});
  }
  commit(SET_DEPARTMENTS_LOADING, false)
}

export function showDepartmentModal({commit}, department) {
  commit(SHOW_DEPARTMENT_MODAL, department)
}

export function hideDepartmentModal({commit}) {
  commit(HIDE_DEPARTMENT_MODAL)
}

export async function saveDepartment({dispatch}, department) {
  let response;
  if (!department.id) {
    response = await httpService.post(`/v1/setup/departments`, department)
  } else {
    response = await httpService.put(`/v1/setup/departments/${department.id}`, department)
  }
  if (response.success) {
    dispatch('getDepartments')
  }

  return response;
}

export async function deleteDepartment({dispatch}, id) {
  let response = await httpService.delete(`/v1/setup/departments/${id}`)
  if (response.success) {
    dispatch('getDepartments')
  }

  return response;
}