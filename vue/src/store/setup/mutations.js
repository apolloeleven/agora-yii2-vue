import {HIDE_COUNTRY_MODAL, SET_COUNTRIES, SET_COUNTRIES_LOADING, SHOW_COUNTRY_MODAL} from './mutation-types';

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
  [SHOW_COUNTRY_MODAL](state) {
    state.countryModal.show = true;
  },
  [HIDE_COUNTRY_MODAL](state) {
    state.countryModal.show = false;
  }
};
