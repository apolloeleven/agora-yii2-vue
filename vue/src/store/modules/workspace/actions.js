import {
  SHOW_WORKSPACE_MODAL,
  HIDE_WORKSPACE_MODAL,
  GET_USER_WORKSPACES,
  WORKSPACE_DELETED
} from './mutation-types';
import workspaceService from "../../../modules/Workspace/workspaceService";

/**
 *
 * @param { function } commit
 * @param { bool } showModal
 */
export function showWorkspaceModal({commit}, showModal) {
  commit(SHOW_WORKSPACE_MODAL, showModal);
}

/**
 *
 * @param { function } commit
 * @param { bool } hideModal
 */
export function hideWorkspaceModal({commit}, hideModal) {
  commit(HIDE_WORKSPACE_MODAL, hideModal);
}

/**
 *
 * @param dispatch
 * @param { Object } workspace
 * @returns {Promise<unknown>}
 */
export async function createWorkspace({dispatch}, workspace) {
  const res = await workspaceService.create(workspace);
  if (res.success) {
    dispatch('getUserWorkspaces');
    return res
  }
}

/**
 *
 * @param dispatch
 * @param { Object } workspace
 * @returns {Promise<unknown>}
 */
export async function updateWorkspace({dispatch}, workspace) {
  const res = await workspaceService.update(workspace);
  if (res.success) {
    dispatch('getUserWorkspaces');
    return res;
  }
}

/**
 *
 * @param { function } commit
 * @param dispatch
 * @param { Object } workspace
 * @returns {Promise<unknown>}
 */
export async function deleteWorkspace({commit, dispatch}, workspace) {
  const res = await workspaceService.delete(workspace.id);
  if (res.success) {
    commit(WORKSPACE_DELETED, workspace.id);
    dispatch('getUserWorkspaces');
    return res;
  }
}

export async function getUserWorkspaces({commit}) {
  const res = await workspaceService.getUserWorkspaces();
  if (res.success) {
    commit(GET_USER_WORKSPACES, res.body);
  }
}