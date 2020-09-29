import {
  SHOW_WORKSPACE_MODAL,
  HIDE_WORKSPACE_MODAL,
  GET_USER_WORKSPACES,
  WORKSPACE_DELETED
} from './mutation-types';
import httpService from "../../../core/services/httpService";

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
 * Create new workspace
 *
 * @param dispatch
 * @param { Object } data
 * @returns {Promise<unknown>}
 */
export async function createWorkspace({dispatch}, data) {
  const res = await httpService.post(`/v1/workspaces/workspace?expand=updatedBy`, data);
  if (res.success) {
    dispatch('getUserWorkspaces');
  }
  return res
}

/**
 * Update workspace
 *
 * @param dispatch
 * @param { Object } data
 * @returns {Promise<unknown>}
 */
export async function updateWorkspace({dispatch}, data) {
  const res = await httpService.put(`/v1/workspaces/workspace/${data.id}`, data);
  if (res.success) {
    dispatch('getUserWorkspaces');
  }
  return res;
}

/**
 *
 * @param { function } commit
 * @param dispatch
 * @param { Object } data
 * @returns {Promise<unknown>}
 */
export async function deleteWorkspace({commit, dispatch}, data) {
  const res = await httpService.delete(`/v1/workspaces/workspace/${data.id}`);
  if (res.success) {
    commit(WORKSPACE_DELETED, data.id);
    dispatch('getUserWorkspaces');
  }
  return res;
}

export async function getUserWorkspaces({commit}) {
  const {success, body} = await httpService.get(`/v1/workspaces/workspace/get-user-workspaces?expand=updatedBy&sort=name`);
  if (success) {
    commit(GET_USER_WORKSPACES, body);
  }
}