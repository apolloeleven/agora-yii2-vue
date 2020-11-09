import {
  SHOW_FOLDER_MODAL,
  HIDE_FOLDER_MODAL,
  GET_ALL_FOLDERS,
  START_LOADING,
  STOP_LOADING,
  FOLDER_DELETED,
  GET_BREAD_CRUMB,
  GET_CURRENT_FOLDER,
  GET_ATTACH_CONFIG,
  GET_FILES,
  SHOW_EDIT_LABEL_DIALOG,
  HIDE_EDIT_LABEL_DIALOG,
  UPDATE_LABEL,
} from "./mutation-types";

export default {
  /**
   * @param state
   * @param { Object } data
   */
  [SHOW_FOLDER_MODAL](state, data) {
    state.showModal = true;
    state.modalFolder = data;
  },
  /**
   * @param state
   */
  [HIDE_FOLDER_MODAL](state) {
    state.showModal = false;
    state.modalFolder = null;
  },
  /**
   * @param state
   * @param folders
   */
  [GET_ALL_FOLDERS](state, folders) {
    state.foldersAndFiles = folders;
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
  [FOLDER_DELETED](state, id) {
    state.foldersAndFiles = state.foldersAndFiles.filter(a => a.id !== id)
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
  [GET_CURRENT_FOLDER](state, data) {
    state.currentFolder = data || {}
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
  [GET_FILES](state, data) {
    state.foldersAndFiles.push(data);
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
};
