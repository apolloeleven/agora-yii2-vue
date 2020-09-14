import { SET_COUNTRIES, SET_COUNTRIES_LOADING } from './mutation-types';

/**
*
* @param { function } commit
*/
export function getCountries({ commit }) {
    commit(SET_COUNTRIES_LOADING, true)
    // Make request to get countries
    const countries = [];
    commit(SET_COUNTRIES, {countries});
    commit(SET_COUNTRIES_LOADING, true)
}
