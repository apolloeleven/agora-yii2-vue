import {
  SHOW_TIMELINE_MODAL,
  GET_TIMELINE_DATA,
  HIDE_TIMELINE_MODAL,
  CHANGE_LOADING,
  DELETED_TIMELINE_POST,
  ADD_TIMELINE_COMMENT,
  DELETE_TIMELINE_COMMENT,
  ADD_TIMELINE_CHILD_COMMENT,
  DELETE_TIMELINE_CHILD_COMMENT,
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

  [ADD_TIMELINE_COMMENT](state, data) {
    state.timelineData.filter(t => t.id === data.timeline_post_id).forEach(t => t.timelineComments.unshift(data));
  },

  [DELETE_TIMELINE_COMMENT](state, data) {
    state.timelineData.forEach(t => t.timelineComments = t.timelineComments.filter(c => c.id !== data.id))
  },

  [ADD_TIMELINE_CHILD_COMMENT](state, data) {
    state.timelineData.filter(t => t.id === data.parent.timeline_post_id)
      .forEach(t => t.timelineComments.filter(tc => tc.id === data.parent_id)
        .forEach(tc => tc.childrenComments.unshift(data)));
  },

  [DELETE_TIMELINE_CHILD_COMMENT](state, data) {
    state.timelineData
      .forEach(t => t.timelineComments
        .forEach(t => t.childrenComments = t.childrenComments.filter(c => c.id !== data.id)))
  },

}