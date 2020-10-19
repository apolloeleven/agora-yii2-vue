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
  LIKE,
  UNLIKE,
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
  },
  /**
   *
   * @param state
   * @param data
   */
  [SHOW_PREVIEW_MODAL](state, data) {
    state.previewModal.show = true;
    state.previewModal.files = data.files;
    state.previewModal.activeFile = data.activeFile;
  },
  /**
   *
   * @param state
   */
  [HIDE_PREVIEW_MODAL](state) {
    state.previewModal.show = false;
  },
  /**
   *
   * @param state
   * @param index
   */
  [CHANGE_CAROUSEL](state, index) {
    state.previewModal.activeFile = index;
  },
  /**
   *
   * @param state
   * @param column
   */
  [SORT_ATTACHMENT](state, column) {
    state.articleFiles = state.articleFiles
      .sort((nextFile, prevFile) => {
        let next;
        let prev;
        let comparison = 0;
        let sortBy = column.sortBy;
        let order = column.sortDesc ? 'desc' : 'asc';

        if (sortBy !== 'updatedBy.displayName') {
          if (!nextFile.hasOwnProperty(sortBy) || !prevFile.hasOwnProperty(sortBy)) {
            return comparison;
          }
          let nextSortKey = sortBy, prevSortKey = sortBy;

          if (sortBy === 'name') {
            nextSortKey = nextFile['label'] ? 'label' : sortBy;
            prevSortKey = prevFile['label'] ? 'label' : sortBy;
          }
          next = typeof nextFile[nextSortKey] === 'string' ? nextFile[nextSortKey].toUpperCase() : nextFile[nextSortKey];
          prev = typeof prevFile[prevSortKey] === 'string' ? prevFile[prevSortKey].toUpperCase() : prevFile[prevSortKey];
        } else {
          let firstKey = 'updatedBy';
          let secondKey = 'displayName';
          if (!nextFile[firstKey].hasOwnProperty(secondKey) || !prevFile[firstKey].hasOwnProperty(secondKey)) {
            return comparison;
          }
          next = typeof nextFile[firstKey][secondKey] === 'string' ? nextFile[firstKey][secondKey].toUpperCase() : nextFile[firstKey][secondKey];
          prev = typeof prevFile[firstKey][secondKey] === 'string' ? prevFile[firstKey][secondKey].toUpperCase() : prevFile[firstKey][secondKey];
        }

        if (next > prev) {
          comparison = 1;
        } else if (next < prev) {
          comparison = -1;
        }
        return order === 'desc' ? comparison * -1 : comparison;
      });
  },
  /**
   *
   * @param state
   * @param data
   */
  [ADD_COMMENT](state, data) {
    state.currentArticle.articleComments.unshift(data);
  },
  /**
   *
   * @param state
   * @param data
   */
  [ADD_CHILD_COMMENT](state, data) {
    state.currentArticle.articleComments.filter(a => a.id === data.parent_id).forEach(a => a.childrenComments.unshift(data));
  },
  /**
   *
   * @param state
   * @param data
   */
  [DELETE_ARTICLE_COMMENT](state, data) {
    state.currentArticle.articleComments = state.currentArticle.articleComments.filter(a => a.id !== data.id);
  },
  /**
   *
   * @param state
   * @param data
   */
  [DELETE_CHILD_COMMENT](state, data) {
    state.currentArticle.articleComments.forEach(a => a.childrenComments = a.childrenComments.filter(c => c.id !== data.id))
  },
  /**
   *
   * @param state
   * @param data
   */
  [LIKE](state, data) {
    state.currentArticle.userLikes.unshift(data)
    state.currentArticle.myLikes.unshift(data)
  },
  /**
   *
   * @param state
   * @param data
   */
  [UNLIKE](state, data) {
    state.currentArticle.myLikes = [];
    state.currentArticle.userLikes = state.currentArticle.userLikes.filter(c => c.id !== data.id);
  },
};
