import {
  SHOW_ARTICLE_MODAL,
  HIDE_ARTICLE_MODAL,
  GET_ALL_ARTICLES,
  START_LOADING,
  STOP_LOADING,
  ARTICLE_DELETED,
  GET_BREAD_CRUMB,
} from "./mutation-types";

export default {
  /**
   * @param state
   * @param { Object } article
   */
  [SHOW_ARTICLE_MODAL](state, article) {
    state.showModal = true;
    state.modalArticle = article;
  },
  /**
   * @param state
   */
  [HIDE_ARTICLE_MODAL](state) {
    state.showModal = false;
    state.modalArticle = null;
  },
  /**
   * @param state
   * @param articles
   */
  [GET_ALL_ARTICLES](state, articles) {
    state.articles = articles;
  },
  /**
   * @param state
   */
  [START_LOADING](state) {
    state.loading = true;
  },
  /**
   * @param state
   */
  [STOP_LOADING](state) {
    state.loading = false;
  },
  /**
   * @param state
   * @param articleId
   */
  [ARTICLE_DELETED](state, articleId) {
    state.articles = state.articles.filter(a => a.id !== articleId)
  },
  /**
   *
   * @param state
   * @param data
   */
  [GET_BREAD_CRUMB](state, data) {
    state.breadCrumb = data
  },
};
