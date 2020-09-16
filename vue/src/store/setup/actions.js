import {HIDE_COUNTRY_MODAL, SET_COUNTRIES, SET_COUNTRIES_LOADING, SHOW_COUNTRY_MODAL} from './mutation-types';
import httpService from "@/core/services/httpService";

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
  if (response) {
    dispatch('getCountries')
  }

  return response;
}