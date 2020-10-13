import {
  SHOW_WORKSPACE_MODAL,
  HIDE_WORKSPACE_MODAL,
  GET_WORKSPACES,
  WORKSPACE_DELETED,
  GET_BREAD_CRUMB,
  GET_CURRENT_WORKSPACE, GET_EMPLOYEES,
} from './mutation-types';
import httpService from "../../../../core/services/httpService";

const url = '/v1/workspaces/workspace'

/**
 * Show workspace form's modal
 *
 * @param { function } commit
 * @param { Object } data
 */
export function showWorkspaceModal({commit}, data) {
  commit(SHOW_WORKSPACE_MODAL, data);
}

/**
 * Hide workspace form's modal
 *
 * @param { function } commit
 */
export function hideWorkspaceModal({commit}) {
  commit(HIDE_WORKSPACE_MODAL);
}

/**
 * Create new workspace
 *
 * @param dispatch
 * @param { Object } data
 * @returns {Promise<unknown>}
 */
export async function createWorkspace({dispatch}, data) {
  const res = await httpService.post(`${url}?expand=updatedBy`, data);
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
  const res = await httpService.put(`${url}/${data.id}`, data);
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