import {
  SHOW_TIMELINE_MODAL,
  SET_TIMELINE_DATA,
  SET_TIMELINE_LOADING,
  HIDE_TIMELINE_MODAL,
  SET_DROPDOWN_DATA
} from './mutation-types'

export default {
  [SHOW_TIMELINE_MODAL](state) {
    state.showModal = true;
  },

  [HIDE_TIMELINE_MODAL](state) {
    state.showModal = false;
  },

  [SET_TIMELINE_LOADING](state, loading) {
    state.loading = loading;
  },

  [SET_TIMELINE_DATA](state, data) {
    state.data = data;
  },

  [SET_DROPDOWN_DATA](state, data) {
    state.dropdownData['userWorkspaces'] = data
  }
}