import {SHOW_ARTICLE_MODAL, HIDE_ARTICLE_MODAL} from "./mutation-types";

export default {
  /**
   *
   * @param state
   */
  [SHOW_ARTICLE_MODAL](state) {
    state.showModal = true;
  },
  /**
   *
   * @param state
   */
  [HIDE_ARTICLE_MODAL](state) {
    state.showModal = false
  },
};
