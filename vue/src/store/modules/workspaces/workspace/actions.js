import {
  SHOW_WORKSPACE_MODAL,
  HIDE_WORKSPACE_MODAL,
  GET_WORKSPACES,
  WORKSPACE_DELETED,
  GET_BREAD_CRUMB,
  GET_CURRENT_WORKSPACE,
  GET_EMPLOYEES,
} from './mutation-types';
import httpService from "../../../../core/services/httpService";

const url = '/v1/workspaces/workspace'

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
  const res = await httpService.post(`${url}?expand=updatedBy`, prepareData(data));
  if (res.success) {
    dispatch('getWorkspaces');
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
    res = await httpService.post(`${url}/${id}`, data);
  } else {
    res = await httpService.put(`${url}/${id}`, data);
  }
  if (res.success) {
    dispatch('getWorkspaces');
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
  const res = await httpService.delete(`${url}/${data.id}`);
  if (res.success) {
    commit(WORKSPACE_DELETED, data.id);
    dispatch('getWorkspaces');
  }
  return res;
}

/**
 * Get current workspace
 *
 * @param commit
 * @param { int } workspaceId
 * @returns {Promise<void>}
 */
export async function getCurrentWorkspace({commit}, workspaceId) {
  const {success, body} = await httpService.get(`${url}/${workspaceId}`)
  if (success) {
    commit(GET_CURRENT_WORKSPACE, body);
  }
}

/**
 *
 * @param commit
 * @param { Object }workspace
 * @returns {Promise<void>}
 */
export async function destroyCurrentWorkspace({commit}, workspace) {
  commit(GET_CURRENT_WORKSPACE, workspace)
}

/**
 * Get workspaces by user
 *
 * @param commit
 * @returns {Promise<void>}
 */
export async function getWorkspaces({commit}) {
  const {success, body} = await httpService.get(`${url}/get-workspaces?expand=updatedBy&sort=name`);
  if (success) {
    commit(GET_WORKSPACES, body);
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
  const res = await httpService.get(`${url}/get-bread-crumb?workspaceId=${workspaceId}`);
  if (res.success) commit(GET_BREAD_CRUMB, res.body);
  return res;
}

/**
 * Get Employees by workspace id
 *
 * @param commit
 * @param workspaceId
 * @returns {Promise<void>}
 */
export async function getEmployees({commit}, workspaceId) {
  const {success, body} = await httpService.get(`${url}/get-employees?workspaceId=${workspaceId}`)
  if (success) {
    commit(GET_EMPLOYEES, body)
  }
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
        tmp.append(key, data[key] || '');
      }
    }
    data = tmp;
  }
  return data;
}