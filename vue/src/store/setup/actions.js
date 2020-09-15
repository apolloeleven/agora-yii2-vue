import { SET_COUNTRIES, SET_COUNTRIES_LOADING } from './mutation-types';
import httpService from "@/core/services/httpService";

/**
*
* @param { function } commit
*/
export async function getCountries({ commit }) {
    commit(SET_COUNTRIES_LOADING, true);
    // Make request to get countries
    const {success, body} = await httpService.get('/v1/setup/countries', {params: {expand: 'createdBy'}})
    if (success) {
        commit(SET_COUNTRIES, {countries: body});
    }
    commit(SET_COUNTRIES_LOADING, false)
}
