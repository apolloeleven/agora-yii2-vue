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
    state.articleFiles = state.articleFiles.sort(compareValues(column.sortBy, column.sortDesc ? 'desc' : 'asc'));
  },
};

function compareValues(sortBy, order = 'asc') {
  return function innerSort(nextFile, prevFile) {
    if (sortBy !== 'updatedBy.displayName') {
      if (!nextFile.hasOwnProperty(sortBy) || !prevFile.hasOwnProperty(sortBy)) {
        return 0;
      }
      let nextFileKey = sortBy, prevFileKey = sortBy;

      switch (sortBy) {
        case 'name':
          nextFile['label'] ? nextFileKey = 'label' : nextFileKey = sortBy;
          prevFile['label'] ? prevFileKey = 'label' : prevFileKey = sortBy;
          break;
      }
      const next = (typeof nextFile[nextFileKey] === 'string') ? nextFile[nextFileKey].toUpperCase() : nextFile[nextFileKey];
      const prev = (typeof prevFile[prevFileKey] === 'string') ? prevFile[prevFileKey].toUpperCase() : prevFile[prevFileKey];
      let comparison = 0;
      if (next > prev) {
        comparison = 1;
      } else if (next < prev) {
        comparison = -1;
      }
      return (
        (order === 'desc') ? (comparison * -1) : comparison
      );
    } else {
      let firstKey = 'updatedBy';
      let secondKey = 'displayName'
      if (!nextFile[firstKey].hasOwnProperty(secondKey) || !prevFile[firstKey].hasOwnProperty(secondKey)) {
        return 0;
      }

      let nextFileKey = (typeof nextFile[firstKey][secondKey] === 'string') ? nextFile[firstKey][secondKey].toUpperCase() : nextFile[firstKey][secondKey];
      let prevFileKey = (typeof prevFile[firstKey][secondKey] === 'string') ? prevFile[firstKey][secondKey].toUpperCase() : prevFile[firstKey][secondKey];

      let comparison = 0;
      if (nextFileKey > prevFileKey) {
        comparison = 1;
      } else if (nextFileKey < prevFileKey) {
        comparison = -1;
      }
      return ((order === 'desc') ? (comparison * -1) : comparison);
    }
  };
}
