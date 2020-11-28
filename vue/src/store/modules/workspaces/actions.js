import {
  ADD_ATTACH_FILES,
  ADD_POLL_ITEM,
  ADD_TIMELINE_CHILD_COMMENT,
  ADD_TIMELINE_COMMENT,
  ADD_TIMELINE_POST,
  ADD_VOTE,
  CHANGE_CAROUSEL,
  CHANGE_TIMELINE_LOADING,
  CHANGE_TIMELINE_MODAL_LOADING,
  CHANGE_WORKSPACE_LOADING,
  CREATE_ARTICLE,
  DELETE_POLL_ITEM,
  DELETE_TIMELINE_CHILD_COMMENT,
  DELETE_TIMELINE_COMMENT,
  DELETED_TIMELINE_POST,
  FOLDER_DELETED,
  GET_ACTIVE_USERS,
  GET_ALL_FOLDERS,
  GET_ARTICLE,
  GET_ARTICLES,
  GET_ATTACH_CONFIG,
  GET_BREAD_CRUMB,
  GET_CURRENT_FOLDER,
  GET_CURRENT_WORKSPACE,
  GET_POLLS_DATA,
  GET_TIMELINE_DATA,
  GET_WORKSPACES,
  HIDE_ARTICLE_MODAL,
  HIDE_FOLDER_MODAL,
  HIDE_INVITE_MODAL,
  HIDE_PREVIEW_MODAL,
  HIDE_TIMELINE_MODAL,
  HIDE_WORKSPACE_MODAL,
  LIKE_TIMELINE_POST,
  REMOVE_ARTICLE,
  SET_WORKSPACE_USERS,
  SHOW_ARTICLE_MODAL,
  SHOW_FOLDER_MODAL,
  SHOW_INVITE_MODAL,
  SHOW_PREVIEW_MODAL,
  SHOW_TIMELINE_MODAL,
  SHOW_WORKSPACE_MODAL,
  SORT_FILES,
  TOGGLE_ARTICLE_VIEW_LOADING,
  TOGGLE_ARTICLES_LOADING,
  TOGGLE_FOLDERS_LOADING,
  TOGGLE_POLLS_LOADING,
  TOGGLE_VIEW_LOADING,
  TOGGLE_WORKSPACE_USERS_LOADING,
  UNLIKE_TIMELINE_POST,
  UPDATE_ARTICLE,
  UPDATE_TIMELINE_POST,
  WORKSPACE_DELETED,
} from './mutation-types';
import httpService from "../../../core/services/httpService";

const url = '/v1/workspaces/workspace';
const articlesUrl = '/v1/workspaces/article';
const timelineUrl = '/v1/workspaces/timeline';
const folderUrl = '/v1/workspaces/folder';
const userUrl = '/v1/users/user';
const userLikeUrl = '/v1/workspaces/user-like';
const userCommentUrl = '/v1/workspaces/user-comment';
const pollUrl = '/v1/workspaces/poll';

const timelineExpand = `expand=article,createdBy,timelineComments.createdBy,timelineComments.childrenComments.createdBy,
timelineComments.childrenComments.parent,userLikes,myLikes&sort=-created_at`;

const pollExpand = `createdBy,pollAnswers.userPollAnswers.createdBy,userPollAllAnswers.createdBy,myVotes`;

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
    params: {expand: 'createdBy,rootFolder'}
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
  commit(CHANGE_WORKSPACE_LOADING, true)
  const {success, body} = await httpService.get(`${url}/get-workspaces?expand=updatedBy&sort=name`);
  if (success) {
    commit(GET_WORKSPACES, body);
    commit(CHANGE_WORKSPACE_LOADING, false)
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
        if (key === 'depth' || key === 'is_file') {
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
  let {success, body} = await httpService.get(articlesUrl, {
    params: {workspace_id, sort: 'title', expand: 'createdBy'}
  })
  if (success) {
    body = body.map((article) => {
      article.showTooltip = false;
      return article
    })
    commit(GET_ARTICLES, body);
  }
  commit(TOGGLE_ARTICLES_LOADING, false);
}

export async function getArticle({commit}, id) {
  commit(TOGGLE_ARTICLE_VIEW_LOADING, true);
  const {success, body} = await httpService.get(`${articlesUrl}/${id}`, {
    params: {expand: 'createdBy'}
  })
  if (success) {
    commit(GET_ARTICLE, body);
  }
  commit(TOGGLE_ARTICLE_VIEW_LOADING, false);
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
  let response = await httpService.post(articlesUrl, data, {
    params: {expand: 'createdBy'}
  });
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
  let response = await httpService.put(`${articlesUrl}/${data.id}`, data, {
    params: {expand: 'createdBy'}
  });
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
  commit(CHANGE_TIMELINE_MODAL_LOADING, true)
  const res = await httpService.post(`${timelineUrl}?${timelineExpand}`, prepareTimelineData(data), config);
  if (res.success) {
    commit(ADD_TIMELINE_POST, res.body);
  }
  commit(CHANGE_TIMELINE_MODAL_LOADING, false)
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

/**
 * Get attachment config data
 *
 * @param commit
 * @returns {Promise<void>}
 */
export async function getAttachConfig({commit}) {
  const {success, body} = await httpService.get(`${folderUrl}/get-attach-config`)
  if (success) {
    commit(GET_ATTACH_CONFIG, body)
  }
}

/**
 * Show folder form modal
 *
 * @param commit
 * @param data
 */
export function showFolderModal({commit}, data) {
  commit(SHOW_FOLDER_MODAL, data);
}

/**
 * Hide folder form modal
 *
 * @param { function } commit
 * @param { bool } hideModal
 */
export function hideFolderModal({commit}, hideModal) {
  commit(HIDE_FOLDER_MODAL, hideModal);
}

/**
 * @param commit
 * @returns {Promise<unknown>}
 * @param { Object } data
 */
export async function createFolder({commit}, data) {
  return await httpService.post(`${folderUrl}?expand=updatedBy`, data);
}

/**
 * @param commit
 * @param { Object } data
 * @returns {Promise<unknown>}
 */
export async function updateFolder({commit}, data) {
  return await httpService.put(`${folderUrl}/${data.id}`, data);
}

/**
 * @param commit
 * @param fileIds
 * @returns {Promise<unknown>}
 */
export async function deleteFolder({commit}, fileIds) {
  const res = await httpService.post(`${folderUrl}/delete-folders`, {fileIds: fileIds});
  if (res.success) {
    commit(FOLDER_DELETED, fileIds)
  }
  return res;
}

/**
 * Get current article
 *
 * @param commit
 * @param { int } folderId
 * @returns {Promise<void>}
 */
export async function getCurrentFolder({commit}, folderId) {
  const {success, body} = await httpService.get(`${folderUrl}/${folderId}?expand=workspace,createdBy`)
  if (success) {
    commit(GET_CURRENT_FOLDER, body);
  }
}

/**
 * Get folders by parent id for article view page
 *
 * @param commit
 * @param { int } parentId
 * @returns {Promise<void>}
 */
export async function getFoldersByParent({commit}, parentId) {
  commit(TOGGLE_FOLDERS_LOADING, true);
  const {success, body} = await httpService.get(`${folderUrl}?parent_id=${parentId}&expand=updatedBy&sort=title`)
  if (success) {
    commit(GET_ALL_FOLDERS, body.data)
    commit(GET_BREAD_CRUMB, {breadcrumbData: body.breadcrumbData, folder: body.currentFolder})
  }
  commit(TOGGLE_FOLDERS_LOADING, false);
}

/**
 * Upload files
 *
 * @param commit
 * @param payload
 * @param data
 * @param config
 * @returns {Promise<unknown>}
 */
export async function attachFiles({commit}, {data, config}) {
  const res = await httpService.post(`${folderUrl}?expand=updatedBy`, prepareFiles(data), config)
  if (res.success) {
    commit(ADD_ATTACH_FILES, res.body)
  }
  return res;
}

/**
 *
 * @param commit
 * @param folder
 * @returns {Promise<void>}
 */
export async function destroyedCurrentFolder({commit}, folder) {
  commit(GET_CURRENT_FOLDER, folder)
}

/**
 * Sort File rows
 *
 * @param commit
 * @param column
 */
export function sortFiles({commit}, column) {
  commit(SORT_FILES, column);
}

/**
 * Prepare file to upload
 *
 * @param data
 * @returns {FormData}
 */
export function prepareFiles(data) {
  const tmp = new FormData();
  for (let key in data.files) {
    if (data.files.hasOwnProperty(key)) {
      tmp.append('files[]', data.files[key], data.files.name);
    }
  }
  for (let key in data) {
    if (data.hasOwnProperty(key) && data[key] !== 'files') {
      tmp.append(key, data[key]);
    }
  }
  data = tmp;
  return data;
}

/**
 * Open file preview modal
 *
 * @param commit
 * @param data
 */
export function showPreviewModal({commit}, data) {
  commit(SHOW_PREVIEW_MODAL, data)
}

/**
 * Close file preview modal
 *
 * @param commit
 */
export function hidePreviewModal({commit}) {
  commit(HIDE_PREVIEW_MODAL)
}

/**
 * Change file preview
 *
 * @param commit
 * @param index
 */
export function changeCarousel({commit}, index) {
  commit(CHANGE_CAROUSEL, index);
}

/**
 *
 * @param commit
 * @param data
 * @returns {Promise<void>}
 */
export async function like({commit}, data) {
  const {success, body} = await httpService.post(`${userLikeUrl}`, data)
  if (success) {
    commit(LIKE_TIMELINE_POST, body)
  }
}

/**
 *
 * @param commit
 * @param data
 * @returns {Promise<void>}
 */
export async function unlike({commit}, data) {
  const {success} = await httpService.delete(`${userLikeUrl}/${data.id}`)
  if (success) {
    commit(UNLIKE_TIMELINE_POST, data)
  }
}


/**
 * @param commit
 * @param data
 * @returns {Promise<unknown>}
 */
export async function addComment({commit}, data) {
  const res = await httpService.post(`${userCommentUrl}?expand=createdBy,childrenComments,parent`, data);
  if (res.success) {
    if (data.parent_id) {
      commit(ADD_TIMELINE_CHILD_COMMENT, res.body)
    }
    commit(ADD_TIMELINE_COMMENT, res.body)

  }
  return res;
}

/**
 * @param commit
 * @param data
 * @returns {Promise<unknown>}
 */
export async function deleteComment({commit}, data) {
  const res = await httpService.delete(`${userCommentUrl}/${data.id}`);
  if (res.success) {
    if (data.parent_id) {
      commit(DELETE_TIMELINE_CHILD_COMMENT, data)
    }
    commit(DELETE_TIMELINE_COMMENT, data)

  }
  return res;
}

/**
 * Open invite modal
 *
 * @param commit
 * @param data
 */
export function showInviteModal({commit}) {
  commit(SHOW_INVITE_MODAL)
}

/**
 * Close invite modal
 *
 * @param commit
 * @param data
 */
export function hideInviteModal({commit}) {
  commit(HIDE_INVITE_MODAL)
}

/**
 *
 * @param commit
 * @returns {Promise<unknown>}
 */
export async function getActiveUsers({commit}) {
  const {success, body} = await httpService.get(`${userUrl}/active-users`)
  if (success) {
    commit(GET_ACTIVE_USERS, body)
  }
}

/**
 *
 * @param commit
 * @param data
 * @returns {Promise<void>}
 */
export async function inviteUsers({commit}, data) {
  return await httpService.post(`${url}/invite-users`, data)
}

/**
 * @param commit
 * @param id
 */
export async function getWorkspaceUsers({commit}, id) {
  commit(TOGGLE_WORKSPACE_USERS_LOADING);
  let {success, body} = await httpService.get(`${url}/get-users?id=${id}`)
  if (success) {
    commit(SET_WORKSPACE_USERS, body);
  }
  commit(TOGGLE_WORKSPACE_USERS_LOADING);
}

/**
 *
 * @param commit
 * @param workspace_id
 * @returns {Promise<unknown>}
 */
export async function getPolls({commit}, workspace_id) {
  commit(TOGGLE_POLLS_LOADING)
  const res = await httpService.get(pollUrl, {
    params: {workspace_id, sort: '-created_at', expand: pollExpand}
  })
  if (res.success) {
    commit(GET_POLLS_DATA, res.body)
  }
  commit(TOGGLE_POLLS_LOADING)
  return res;
}

/**
 *
 * @param commit
 * @param data
 * @returns {Promise<unknown>}
 */
export async function createPoll({commit}, data) {
  commit(TOGGLE_POLLS_LOADING)
  const res = await httpService.post(`${pollUrl}?expand=${pollExpand}`, data);
  if (res.success) {
    commit(ADD_POLL_ITEM, res.body)
  }
  commit(TOGGLE_POLLS_LOADING)
  return res;
}

/**
 *
 * @param commit
 * @param data
 * @returns {Promise<unknown>}
 */
export async function deletePoll({commit}, data) {
  commit(TOGGLE_POLLS_LOADING)
  const res = await httpService.delete(`${pollUrl}/${data.id}`);
  if (res.success) {
    commit(DELETE_POLL_ITEM, data)
  }
  commit(TOGGLE_POLLS_LOADING)
  return res;
}

/**
 *
 * @param commit
 * @param selected
 * @param item
 * @returns {Promise<unknown>}
 */
export async function addVote({commit}, {selected, item}) {
  const res = await httpService.post(`${pollUrl}/add-vote`, {answerIds: selected});
  if (res.success) {
    commit(ADD_VOTE, {selected, item, data: res.body})
  }
  return res;
}