import {
  SHOW_WORKSPACE_MODAL,
  HIDE_WORKSPACE_MODAL,
  GET_USER_WORKSPACES,
  WORKSPACE_DELETED,
  GET_BREAD_CRUMB
} from './mutation-types';

export default {
  /**
   *
   * @param state
   * @param workspace
   */
  [SHOW_WORKSPACE_MODAL](state, workspace) {
    state.showModal = true;
    state.modalWorkspace = workspace;
  },
  /**
   *
   * @param state
   */
  [HIDE_WORKSPACE_MODAL](state) {
    state.showModal = false;
    state.modalWorkspace = null;
  },
  /**
   *
   * @param state
   * @param data
   */
  [GET_USER_WORKSPACES](state, data) {
    state.userWorkspaces = data;
    state.workspaces = data;
  },
  /**
   *
   * @param state
   * @param id
   */
  [WORKSPACE_DELETED](state, id) {
    state.workspaces = state.workspaces.filter(workspace => workspace.id !== id)
  },
  /**
   *
   * @param state
   * @param data
   */
  [GET_BREAD_CRUMB](state, data) {
    state.breadCrumb = data
  },
};
