import {SET_COUNTRIES, SET_COUNTRIES_LOADING} from './mutation-types';

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
    state.countries.data = countries;
  },
};
