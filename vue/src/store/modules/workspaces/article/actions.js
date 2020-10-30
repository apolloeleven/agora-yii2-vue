import {
  SHOW_ARTICLE_MODAL,
  HIDE_ARTICLE_MODAL,
  GET_ALL_ARTICLES,
  START_LOADING,
  STOP_LOADING,
  ARTICLE_DELETED,
  GET_BREAD_CRUMB,
  GET_CURRENT_ARTICLE,
  GET_ATTACH_CONFIG,
  GET_ARTICLES_FILES,
  SHOW_EDIT_LABEL_DIALOG,
  HIDE_EDIT_LABEL_DIALOG,
  UPDATE_LABEL,
  SHOW_PREVIEW_MODAL,
  HIDE_PREVIEW_MODAL,
  CHANGE_CAROUSEL,
  SORT_ATTACHMENT,
  ADD_CHILD_COMMENT,
  DELETE_ARTICLE_COMMENT,
  DELETE_CHILD_COMMENT,
  ADD_COMMENT,
} from './mutation-types';
import httpService from "../../../../core/services/httpService";
import store from "../../../index";

const url = '/v1/workspaces/article';
const fileUrl = '/v1/workspaces/article-file';

/**
 * Show article form's modal
 *
 * @param { function } commit
 * @param data
 * @param { bool } isArticle
 */
export function showArticleModal({commit}, data) {
  commit(SHOW_ARTICLE_MODAL, data);
}

/**
 * Hide article form's modal
 *
 * @param { function } commit
 * @param { bool } hideModal
 */
export function hideArticleModal({commit}, hideModal) {
  commit(HIDE_ARTICLE_MODAL, hideModal);
}

/**
 * @param payload
 * @returns {Promise<unknown>}
 * @param { Object } data
 */
export async function createArticle({dispatch}, data) {
  return await httpService.post(url, prepareData(data));
}

/**
 * @param dispatch
 * @param { Object } data
 * @returns {Promise<unknown>}
 */
export async function updateArticle({dispatch}, data) {
  const id = data.id;
  data = prepareData(data);

  if (data instanceof FormData) {
    data.append('_method', 'PUT');
    return await httpService.post(`${url}/${id}`, data);
  } else {
    return await httpService.put(`${url}/${id}`, data);
  }
}

/**
 * @param commit
 * @param { Object } data
 * @returns {Promise<unknown>}
 */
export async function deleteArticle({commit}, data) {
  const res = await httpService.delete(`${url}/${data.id}`);
  if (res.success) {
    commit(ARTICLE_DELETED, data.id)
  }
  return res;
}

/**
 * Get current article
 *
 * @param commit
 * @param { int } articleId
 * @returns {Promise<void>}
 */
export async function getCurrentArticle({commit}, articleId) {
  const {success, body} = await httpService.get(`${url}/${articleId}?expand=workspace,createdBy,
    articleComments.createdBy,articleComments.childrenComments.createdBy,articleComments.childrenComments.parent`)
  if (success) {
    commit(GET_CURRENT_ARTICLE, body);
  }
}

/**
 *
 * @param commit
 * @param { Object } article
 * @returns {Promise<void>}
 */
export async function destroyCurrentArticle({commit}, article) {
  commit(GET_CURRENT_ARTICLE, article)
}

/**
 * Get articles for workspace view
 *
 * @param commit
 * @param { int } workspaceId
 * @returns {Promise<void>}
 */
export async function getArticlesByWorkspace({commit}, workspaceId) {
  const {success, body} = await httpService.get(`${url}?workspace_id=${workspaceId}&sort=title`)
  if (success) {
    commit(GET_ALL_ARTICLES, body)
    commit(START_LOADING)
  }
  commit(STOP_LOADING)
}

/**
 * Get articles by parent id for article view page
 *
 * @param commit
 * @param { int } parentId
 * @returns {Promise<void>}
 */
export async function getArticlesByParent({commit}, parentId) {
  const {success, body} = await httpService.get(`${url}/by-parent?articleId=${parentId}&sort=title`)
  if (success) {
    commit(GET_ALL_ARTICLES, body)
    commit(START_LOADING)
  }
  commit(STOP_LOADING)
}

/**
 * Get breadcrumb for article view page
 *
 * @param commit
 * @param { int } articleId
 * @returns {Promise<void>}
 */
export async function getArticleBreadCrumb({commit}, articleId) {
  const res = await httpService.get(`${url}/get-bread-crumb?articleId=${articleId}`);
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
        if (key === 'depth' || key === 'is_folder') {
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

/** Article Files Actions */

/**
 * Get attachment config data
 *
 * @param commit
 * @returns {Promise<void>}
 */
export async function getAttachConfig({commit}) {
  const {success, body} = await httpService.get(`${fileUrl}/get-attach-config`)
  if (success) {
    commit(GET_ATTACH_CONFIG, body)
  }
}

/**
 * Upload files
 *
 * @param dispatch
 * @param payload
 * @param data
 * @param config
 * @returns {Promise<unknown>}
 */
export async function attachFiles({dispatch}, {data, config}) {
  const res = await httpService.post(`${fileUrl}/attach-files`, prepareFiles(data), config)
  if (res.success) {
    dispatch('getFilesByArticle', data.article_id);
  }
  return res;
}

/**
 * Get article files data by article
 *
 * @param commit
 * @param articleId
 * @returns {Promise<void>}
 */
export async function getFilesByArticle({commit}, articleId) {
  const {success, body} = await httpService.get(fileUrl, {
    params: {
      articleId,
      expand: 'updatedBy',
      sort: 'name'
    }
  });
  if (success) {
    commit(GET_ARTICLES_FILES, body);
  }
}

/**
 * Show edit label dialog form
 *
 * @param commit
 * @param file
 */
export function showEditLabelDialog({commit}, file) {
  commit(SHOW_EDIT_LABEL_DIALOG, file);
}

/**
 * Hide edit label dialog form
 *
 * @param commit
 */
export function hideEditLabelDialog({commit}) {
  commit(HIDE_EDIT_LABEL_DIALOG);
}

/**
 * Update files label
 *
 * @param commit
 * @param id
 * @param label
 * @returns {Promise<unknown>}
 */
export async function updateLabel({commit}, {id, label}) {
  const res = await httpService.post(`${fileUrl}/change-label`, {id, label})
  if (res.success) {
    commit(UPDATE_LABEL, {id, label})
  }
  return res;
}

/**
 * Delete attachments
 *
 * @param dispatch
 * @param data
 * @returns {Promise<void>}
 */
export async function deleteAttachments({dispatch}, data) {
  const res = await httpService.post(`${fileUrl}/delete-attachments`, data)
  if (res.success) {
    dispatch('getFilesByArticle', data.article_id);
    dispatch('hidePreviewModal');
  }
  return res;
}

/**
 * Open attachment preview modal
 *
 * @param commit
 * @param data
 */
export function showPreviewModal({commit}, data) {
  commit(SHOW_PREVIEW_MODAL, data)
}

/**
 * Close attachment preview modal
 *
 * @param commit
 */
export function hidePreviewModal({commit}) {
  commit(HIDE_PREVIEW_MODAL)
}

/**
 * Change attachment preview
 *
 * @param commit
 * @param index
 */
export function changeCarousel({commit}, index) {
  commit(CHANGE_CAROUSEL, index);
}

/**
 * Sort attachment rows
 *
 * @param commit
 * @param column
 */
export function sortAttachment({commit}, column) {
  commit(SORT_ATTACHMENT, column);
}

/**
 * @param commit
 * @param data
 * @returns {Promise<unknown>}
 */
export async function addComment({commit}, data) {
  const res = await httpService.post(`/v1/workspaces/user-comment?expand=createdBy,childrenComments,parent`, data);
  if (res.success) {
    if (data.article_id) {
      commit(ADD_COMMENT, res.body);
    } else if (data.parent_id) {
      if (res.body.parent.article_id) {
        commit(ADD_CHILD_COMMENT, res.body)
      } else {
        store.commit('timeline/timeline/ADD_TIMELINE_CHILD_COMMENT', res.body)
      }
    } else {
      store.commit('timeline/timeline/ADD_TIMELINE_COMMENT', res.body)
    }
  }
  return res;
}

/**
 * @param commit
 * @param data
 * @returns {Promise<unknown>}
 */
export async function deleteComment({commit}, data) {
  const res = await httpService.delete(`/v1/workspaces/user-comment/${data.id}`);
  if (res.success) {
    if (data.article_id) {
      commit(DELETE_ARTICLE_COMMENT, data);
    } else if (data.parent_id) {
      if (data.parent.article_id) {
        commit(DELETE_CHILD_COMMENT, data)
      } else {
        store.commit('timeline/timeline/DELETE_TIMELINE_CHILD_COMMENT', data)
      }
    } else {
      store.commit('timeline/timeline/DELETE_TIMELINE_COMMENT', data)
    }
  }
  return res;
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