import {
  SHOW_WORKSPACE_MODAL,
  HIDE_WORKSPACE_MODAL,
  GET_WORKSPACES,
  WORKSPACE_DELETED,
  GET_BREAD_CRUMB,
  GET_CURRENT_WORKSPACE,
  GET_EMPLOYEES,
  GET_ARTICLES,
  TOGGLE_ARTICLES_LOADING,
  SHOW_ARTICLE_MODAL,
  HIDE_ARTICLE_MODAL,
  CREATE_ARTICLE,
  UPDATE_ARTICLE,
  REMOVE_ARTICLE,
} from './mutation-types';
import _ from 'lodash';

export default {
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
  [GET_BREAD_CRUMB](state, data) {
    state.breadCrumb = data
  },
  /**
   * @param state
   * @param data
   */
  [GET_CURRENT_WORKSPACE](state, data) {
    state.currentWorkspace = data || {}
  },
  /**
   *
   * @param state
   * @param data
   */
  [GET_EMPLOYEES](state, data) {
    state.employees = data;
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
};
