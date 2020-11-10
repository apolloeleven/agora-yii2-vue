import {
  SHOW_WORKSPACE_MODAL,
  HIDE_WORKSPACE_MODAL,
  GET_WORKSPACES,
  WORKSPACE_DELETED,
  GET_CURRENT_WORKSPACE,
  TOGGLE_VIEW_LOADING,
  GET_ARTICLES,
  TOGGLE_ARTICLES_LOADING,
  SHOW_ARTICLE_MODAL,
  HIDE_ARTICLE_MODAL,
  UPDATE_ARTICLE,
  CREATE_ARTICLE,
  REMOVE_ARTICLE,
  SHOW_TIMELINE_MODAL,
  GET_TIMELINE_DATA,
  CHANGE_TIMELINE_LOADING,
  HIDE_TIMELINE_MODAL,
  DELETED_TIMELINE_POST,
  ADD_TIMELINE_POST,
  UPDATE_TIMELINE_POST,
} from './mutation-types';
import httpService from "../../../core/services/httpService";

const url = '/v1/workspaces/workspace';
const articlesUrl = '/v1/workspaces/article';
const timelineUrl = '/v1/workspaces/timeline';

const timelineExpand = `expand=article,createdBy,timelineComments.createdBy,timelineComments.childrenComments.createdBy,
timelineComments.childrenComments.parent,userLikes,myLikes&sort=-created_at`

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
  commit(TOGGLE_VIEW_LOADING, true);
  const response = await httpService.get(`${url}/${workspaceId}`, {
    params: {expand: 'createdBy'}
  })
  if (response.success) {
    commit(GET_CURRENT_WORKSPACE, response.body);
  }
  commit(TOGGLE_VIEW_LOADING, false);
  return response;
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
        if (key === 'folder_in_folder') {
          tmp.append(key, data[key]);
        } else {
          tmp.append(key, data[key] || '');
        }
      }
    }
    data = tmp;
  }
  return data;
}

/**
 *
 * @param commit
 * @param workspace_id
 * @returns {Promise<void>}
 */
export async function getArticles({commit}, workspace_id) {
  commit(TOGGLE_ARTICLES_LOADING, true);
  const {success, body} = await httpService.get(articlesUrl, {
    params: {workspace_id, sort: 'title'}
  })
  if (success) {
    commit(GET_ARTICLES, body);
  }
  commit(TOGGLE_ARTICLES_LOADING, false);
}

/**
 *
 * @param commit
 * @param data
 */
export function showArticleModal({commit}, data) {
  commit(SHOW_ARTICLE_MODAL, data);
}

/**
 *
 * @param commit
 */
export function hideArticleModal({commit}) {
  commit(HIDE_ARTICLE_MODAL);
}

/**
 *
 * @param commit
 * @param data
 * @returns {Promise<unknown>}
 */
export async function createArticle({commit}, data) {
  let response = await httpService.post(articlesUrl, data);
  if (response.success) {
    commit(CREATE_ARTICLE, response.body);
  }
  return response;
}

/**
 *
 * @param commit
 * @param data
 * @returns {Promise<unknown>}
 */
export async function updateArticle({commit}, data) {
  let response = await httpService.put(`${articlesUrl}/${data.id}`, data);
  if (response.success) {
    commit(UPDATE_ARTICLE, response.body);
  }
  return response;
}

/**
 *
 * @param commit
 * @param id
 * @returns {Promise<unknown>}
 */
export async function deleteArticle({commit}, id) {
  const response = await httpService.delete(`${articlesUrl}/${id}`);
  if (response.success) {
    commit(REMOVE_ARTICLE, id);
  }
  return response;
}

/**
 *
 * @param commit
 * @param data
 */
export function showTimelineModal({commit}, data) {
  commit(SHOW_TIMELINE_MODAL, data)
}

/**
 *
 * @param commit
 */
export function hideTimelineModal({commit}) {
  commit(HIDE_TIMELINE_MODAL)
}

/**
 *
 * @param commit
 * @param workspaceId
 * @returns {Promise<unknown>}
 */
export async function getTimelinePosts({commit}, workspaceId) {
  commit(CHANGE_TIMELINE_LOADING)
  const res = await httpService.get(`${timelineUrl}?workspace_id=${workspaceId}&${timelineExpand}`);
  if (res.success) {
    commit(CHANGE_TIMELINE_LOADING)
    commit(GET_TIMELINE_DATA, res.body);
  }
  return res;
}

/**
 *
 * @param commit
 * @param data
 * @returns {Promise<unknown>}
 */
export async function deleteTimelinePost({commit}, data) {
  const res = await httpService.delete(`${timelineUrl}/${data.id}?${timelineExpand}`);
  if (res.success) {
    commit(DELETED_TIMELINE_POST, data.id);
  }
  return res;
}

/**
 *
 * @param commit
 * @param data
 * @returns {Promise<unknown>}
 */
export async function updateTimelinePost({commit}, data) {
  const res = await httpService.put(`${timelineUrl}/${data.id}`, data);
  if (res.success) {
    commit(UPDATE_TIMELINE_POST, res.body);
  }
  return res;
}

/**
 *
 * @param commit
 * @param data
 * @param config
 * @returns {Promise<unknown>}
 */
export async function postOnTimeline({commit}, {data, config}) {
  const res = await httpService.post(`${timelineUrl}?${timelineExpand}`, prepareTimelineData(data), config);
  if (res.success) {
    commit(ADD_TIMELINE_POST, res.body);
  }
  return res;
}

/**
 *
 * @param data
 * @returns {*}
 */
export function prepareTimelineData(data) {
  if (data.file && data.file instanceof File) {
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