import {SET_USER, SET_PROFILE_LOADING, SET_PASSWORD_FORM_LOADING} from './mutation-types';

export default {
  /**
   *
   * @param { UserState } state
   * @param { array } userProfile
   */
  [SET_USER](state, userProfile) {
    state.currentUser.data = userProfile;
    state.currentUser.loaded = true;
  },

  /**
   *
   * @param { UserState } state
   * @param { boolean } loading
   */
  [SET_PROFILE_LOADING](state, loading) {
    state.currentUser.loading = loading;
  },
  [SET_PASSWORD_FORM_LOADING](state, loading) {
    state.passwordForm.loading = loading;
  }
};
