import {
  ADD_TIMELINE_CHILD_COMMENT,
  ADD_TIMELINE_COMMENT,
  ADD_TIMELINE_POST,
  CHANGE_TIMELINE_LOADING,
  CHANGE_TIMELINE_MODAL_LOADING,
  CHANGE_WORKSPACE_LOADING,
  CREATE_ARTICLE,
  TOGGLE_ARTICLE_VIEW_LOADING,
  GET_ARTICLE,
  DELETE_TIMELINE_CHILD_COMMENT,
  DELETE_TIMELINE_COMMENT,
  DELETED_TIMELINE_POST,
  GET_ARTICLES,
  GET_CURRENT_WORKSPACE,
  GET_TIMELINE_DATA,
  GET_WORKSPACES,
  HIDE_ARTICLE_MODAL,
  HIDE_TIMELINE_MODAL,
  HIDE_WORKSPACE_MODAL,
  REMOVE_ARTICLE,
  SHOW_ARTICLE_MODAL,
  SHOW_TIMELINE_MODAL,
  SHOW_WORKSPACE_MODAL,
  TIMELINE_LIKE,
  TIMELINE_UNLIKE,
  TOGGLE_ARTICLES_LOADING,
  TOGGLE_VIEW_LOADING,
  UPDATE_ARTICLE,
  UPDATE_TIMELINE_POST,
  WORKSPACE_DELETED,
} from './mutation-types';
import _ from 'lodash';

export default {
  /**
   * @param state
   * @param loading
   */
  [CHANGE_WORKSPACE_LOADING](state, loading) {
    state.loading = loading;
  },
  /**
   * @param state
   * @param workspace
   */
  [SHOW_WORKSPACE_MODAL](state, workspace) {
    state.showModal = true;
    state.modalWorkspace = workspace;
  },
  /**
   * @param state
   */
  [HIDE_WORKSPACE_MODAL](state) {
    state.showModal = false;
    state.modalWorkspace = null;
  },
  /**
   * @param state
   * @param data
   */
  [GET_WORKSPACES](state, data) {
    state.workspaces = data;
  },
  /**
   * @param state
   * @param id
   */
  [WORKSPACE_DELETED](state, id) {
    state.workspaces = state.workspaces.filter(w => w.id !== id)
  },
  /**
   * @param state
   * @param data
   */
  [GET_CURRENT_WORKSPACE](state, data) {
    state.view.workspace = data || {}
  },
  /**
   * @param state
   * @param data
   */
  [TOGGLE_VIEW_LOADING](state, data) {
    state.view.loading = data
  },

  /**
   *
   * @param state
   * @param data
   */
  [GET_ARTICLES](state, data) {
    state.view.articles.data = data;
  },
  /**
   *
   * @param state
   * @param data
   */
  [TOGGLE_ARTICLE_VIEW_LOADING](state, data) {
    state.view.articles.view.loading = data;
  },
  /**
   *
   * @param state
   * @param data
   */
  [GET_ARTICLE](state, data) {
    state.view.articles.view.article = data;
  },
  /**
   *
   * @param state
   * @param data
   */
  [TOGGLE_ARTICLES_LOADING](state, data) {
    state.view.articles.loading = data;
  },
  /**
   *
   * @param state
   * @param data
   */
  [SHOW_ARTICLE_MODAL](state, data) {
    state.view.articles.modal.show = true;
    state.view.articles.modal.object = _.cloneDeep(data.object);
  },
  /**
   *
   * @param state
   */
  [HIDE_ARTICLE_MODAL](state) {
    state.view.articles.modal.show = false;
    state.view.articles.modal.object = null;
  },
  /**
   *
   * @param state
   * @param data
   */
  [CREATE_ARTICLE](state, data) {
    state.view.articles.data.unshift(data);
  },
  /**
   *
   * @param state
   * @param article
   */
  [UPDATE_ARTICLE](state, article) {
    let index = state.view.articles.data.findIndex(a => a.id === article.id);
    state.view.articles.data[index] = {...state.view.articles.data[index], ...article};
    state.view.articles.data = [...state.view.articles.data];
  },
  /**
   *
   * @param state
   * @param id
   */
  [REMOVE_ARTICLE](state, id) {
    const rows = state.view.articles.data.filter(a => a.id !== id);
    state.view.articles.data = [...rows];
  },

  /**
   *
   * @param state
   * @param data
   */
  [SHOW_TIMELINE_MODAL](state, data) {
    state.view.timeline.modal.show = true;
    state.view.timeline.modal.object = _.cloneDeep(data);
  },
  /**
   *
   * @param state
   */
  [HIDE_TIMELINE_MODAL](state) {
    state.view.timeline.modal.show = false;
    state.view.timeline.modal.object = null;
  },
  /**
   *
   * @param state
   * @param data
   */
  [GET_TIMELINE_DATA](state, data) {
    state.view.timeline.loading = false;
    state.view.timeline.data = data;
  },
  /**
   *
   * @param state
   */
  [CHANGE_TIMELINE_LOADING](state) {
    state.view.timeline.loading = !state.view.timeline.loading;
  },
  /**
   *
   * @param state
   * @param data
   */
  [ADD_TIMELINE_POST](state, data) {
    state.view.timeline.data.unshift(data);
  },
  /**
   *
   * @param state
   * @param timeline
   */
  [UPDATE_TIMELINE_POST](state, timeline) {
    let index = state.view.timeline.data.findIndex(a => a.id === timeline.id);
    state.view.timeline.data[index] = {...state.view.timeline.data[index], ...timeline};
    state.view.timeline.data = [...state.view.timeline.data];
  },
  /**
   *
   * @param state
   * @param id
   */
  [DELETED_TIMELINE_POST](state, id) {
    state.view.timeline.data = state.view.timeline.data.filter(t => t.id !== id);
  },
  /**
   *
   * @param state
   * @param data
   */
  [ADD_TIMELINE_COMMENT](state, data) {
    state.view.timeline.data.filter(t => t.id === data.timeline_post_id).forEach(t => t.timelineComments.unshift(data));
  },
  /**
   *
   * @param state
   * @param data
   */
  [DELETE_TIMELINE_COMMENT](state, data) {
    state.view.timeline.data.forEach(t => t.timelineComments = t.timelineComments.filter(c => c.id !== data.id));
  },
  /**
   *
   * @param state
   * @param data
   */
  [ADD_TIMELINE_CHILD_COMMENT](state, data) {
    state.view.timeline.data.filter(t => t.id === data.parent.timeline_post_id)
      .forEach(t => t.timelineComments.filter(tc => tc.id === data.parent_id)
        .forEach(tc => tc.childrenComments.unshift(data)));
  },
  /**
   *
   * @param state
   * @param data
   */
  [DELETE_TIMELINE_CHILD_COMMENT](state, data) {
    state.view.timeline.data
      .forEach(t => t.timelineComments
        .forEach(t => t.childrenComments = t.childrenComments.filter(c => c.id !== data.id)));
  },
  /**
   *
   * @param state
   * @param data
   */
  [TIMELINE_LIKE](state, data) {
    const timelinePost = state.view.timeline.data.filter(t => t.id === data.timeline_post_id);
    timelinePost.forEach(t => t.userLikes.unshift(data));
    timelinePost.forEach(t => t.myLikes.unshift(data));
  },
  /**
   *
   * @param state
   * @param data
   */
  [TIMELINE_UNLIKE](state, data) {
    const timelinePost = state.view.timeline.data.filter(t => t.id === data.timeline_post_id);
    timelinePost.forEach(t => t.myLikes = []);
    timelinePost.forEach(t => t.userLikes = t.userLikes.filter(l => l.id !== data.id));
  },
  /**
   *
   * @param state
   * @param loading
   */
  [CHANGE_TIMELINE_MODAL_LOADING](state, loading) {
    state.view.timeline.modal.loading = loading
  },

};
