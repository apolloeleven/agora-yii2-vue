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
} from "./mutation-types";

export default {
  /**
   * @param state
   * @param { Object } data
   */
  [SHOW_ARTICLE_MODAL](state, data) {
    state.showModal = true;
    state.modalArticle = data.article;
    state.isArticle = data.isArticle;
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
   * @param id
   */
  [ARTICLE_DELETED](state, id) {
    state.articles = state.articles.filter(a => a.id !== id)
  },
  /**
   *
   * @param state
   * @param data
   */
  [GET_BREAD_CRUMB](state, data) {
    state.breadCrumb = data
  },
  /**
   *
   * @param state
   * @param data
   */
  [GET_CURRENT_ARTICLE](state, data) {
    state.currentArticle = data || {}
  },
  /**
   *
   * @param state
   * @param data
   */
  [GET_ATTACH_CONFIG](state, data) {
    state.attachConfig = data
  },
  /**
   *
   * @param state
   * @param data
   */
  [GET_ARTICLES_FILES](state, data) {
    state.articleFiles = data
  },
  /**
   *
   * @param state
   * @param data
   */
  [SHOW_EDIT_LABEL_DIALOG](state, data) {
    state.articleFile.showModal = true;
    state.articleFile.file = data;
  },
  /**
   *
   * @param state
   */
  [HIDE_EDIT_LABEL_DIALOG](state) {
    state.articleFile.showModal = false;
    state.articleFile.file = null;
  },
  /**
   *
   * @param state
   * @param id
   * @param label
   */
  [UPDATE_LABEL](state, {id, label}) {
    const file = state.articleFiles.find(a => a.id === id);
    if (file) file.label = label;
  }
};
