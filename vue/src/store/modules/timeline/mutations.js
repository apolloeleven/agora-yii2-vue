import {
  SHOW_TIMELINE_MODAL,
  GET_TIMELINE_DATA,
  HIDE_TIMELINE_MODAL,
  CHANGE_LOADING,
  DELETED_TIMELINE_POST,
} from './mutation-types'

export default {
  [SHOW_TIMELINE_MODAL](state, data) {
    state.showModal = true;
    state.modalTimeline = data;
  },

  [HIDE_TIMELINE_MODAL](state) {
    state.showModal = false;
    state.modalTimeline = null;
  },

  [GET_TIMELINE_DATA](state, data) {
    state.loading = false;
    state.timelineData = data;
  },

  [CHANGE_LOADING](state) {
    state.loading = !state.loading;
  },

  [DELETED_TIMELINE_POST](state, id) {
    state.timelineData = state.timelineData.filter(t => t.id !== id)
  },

}