import {
  SHOW_WORKSPACE_MODAL,
  HIDE_WORKSPACE_MODAL,
  GET_WORKSPACES,
  WORKSPACE_DELETED,
  GET_BREAD_CRUMB,
  GET_CURRENT_WORKSPACE,
} from './mutation-types';

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
  }
};
