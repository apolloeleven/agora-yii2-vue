import {
  SHOW_ARTICLE_MODAL,
  HIDE_ARTICLE_MODAL,
  GET_ALL_ARTICLES,
  START_LOADING,
  STOP_LOADING,
  ARTICLE_DELETED,
  GET_BREAD_CRUMB,
  GET_CURRENT_ARTICLE,
} from './mutation-types';
import httpService from "../../../../core/services/httpService";

const url = '/v1/workspaces/article';

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
  return await httpService.post(url, data);
}

/**
 * @param dispatch
 * @param { Object } data
 * @returns {Promise<unknown>}
 */
export async function updateArticle({dispatch}, data) {
  return await httpService.put(`${url}/${data.id}`, data);
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
  const {success, body} = await httpService.get(`${url}/${articleId}?expand=workspace`)
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
 * @param parentId
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
