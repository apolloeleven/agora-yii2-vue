import {
  SHOW_ARTICLE_MODAL,
  HIDE_ARTICLE_MODAL,
} from './mutation-types';

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