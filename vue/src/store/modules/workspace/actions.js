import {
  SHOW_WORKSPACE_MODAL,
  HIDE_WORKSPACE_MODAL,
  GET_USER_WORKSPACES,
  WORKSPACE_DELETED,
  GET_BREAD_CRUMB
} from './mutation-types';
import httpService from "../../../core/services/httpService";

/**
 * Show workspace form's modal
 *
 * @param { function } commit
 * @param { bool } showModal
 */
export function showWorkspaceModal({commit}, showModal) {
  commit(SHOW_WORKSPACE_MODAL, showModal);
}

/**
 * Hide workspace form's modal
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
  const res = await httpService.post(`/v1/workspaces/workspace?expand=updatedBy`, prepareData(data));
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
  const id = data.id;
  data = prepareData(data);
  let res;

  if (data instanceof FormData) {
    data.append('_method', 'PUT');
    res = await httpService.post(`/v1/workspaces/workspace/${id}`, data)
  } else {
    res = await httpService.put(`/v1/workspaces/workspace/${id}`, data)
  }

  if (res.success) {
    dispatch('getUserWorkspaces');
  }
  return res;
}

/**
 * Delete workspace
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

/**
 * Get workspaces by user
 *
 * @param commit
 * @returns {Promise<void>}
 */
export async function getUserWorkspaces({commit}) {
  const {success, body} = await httpService.get(`/v1/workspaces/workspace/get-user-workspaces?expand=updatedBy&sort=name`);
  if (success) {
    commit(GET_USER_WORKSPACES, body);
  }
}

/**
 * Get breadcrumb for workspace view page
 *
 * @param commit
 * @param workspaceId
 * @returns {Promise<void>}
 */
export async function getWorkspaceBreadCrumb({commit}, workspaceId) {
  const res = await httpService.get(`/v1/workspaces/workspace/get-bread-crumb?workspaceId=${workspaceId}`);
  if (res.success) commit(GET_BREAD_CRUMB, res.body);
  return res;
}

/**
 * Prepare data for upload
 *
 * @param data
 * @returns {*}
 */
export function prepareData(data) {
  if (data.image && data.image instanceof File) {
    const tmp = new FormData();
    for (let key in data) {
      if (data.hasOwnProperty(key)) {
        tmp.append(key, data[key]);
      }
    }
    data = tmp;
  }
  return data;
}