import {
  SHOW_ARTICLE_MODAL,
  HIDE_ARTICLE_MODAL,
} from './mutation-types';
import httpService from "../../../../core/services/httpService";

const url = '/v1/workspaces/article';

/**
 * Show article form's modal
 *
 * @param { function } commit
 * @param { bool } showModal
 */
export function showArticleModal({commit}, showModal) {
  commit(SHOW_ARTICLE_MODAL, showModal);
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
 * Create article
 *
 * @param payload
 * @returns {Promise<unknown>}
 * @param { Object } data
 */
export async function createArticle({}, data) {
  return await httpService.post(url, data)
}