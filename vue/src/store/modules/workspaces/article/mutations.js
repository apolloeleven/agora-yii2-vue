import {SHOW_ARTICLE_MODAL, HIDE_ARTICLE_MODAL} from "./mutation-types";

export default {
  /**
   *
   * @param state
   * @param { Object } article
   */
  [SHOW_ARTICLE_MODAL](state, article) {
    state.showModal = true;
    state.modalArticle = article;
  },
  /**
   *
   * @param state
   */
  [HIDE_ARTICLE_MODAL](state) {
    state.showModal = false;
    state.modalArticle = null;
  },
};
