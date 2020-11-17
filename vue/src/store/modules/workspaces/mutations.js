import {
  ADD_ATTACH_FILES,
  ADD_TIMELINE_POST,
  CHANGE_CAROUSEL,
  CHANGE_TIMELINE_LOADING,
  CHANGE_TIMELINE_MODAL_LOADING,
  CHANGE_WORKSPACE_LOADING,
  CREATE_ARTICLE,
  DELETED_TIMELINE_POST,
  FOLDER_DELETED,
  GET_ALL_FOLDERS,
  GET_ALL_USER,
  GET_ARTICLE,
  GET_ARTICLES,
  GET_ATTACH_CONFIG,
  GET_BREAD_CRUMB,
  GET_CURRENT_FOLDER,
  GET_CURRENT_WORKSPACE,
  GET_TIMELINE_DATA,
  GET_WORKSPACES,
  HIDE_ARTICLE_MODAL,
  HIDE_FOLDER_MODAL,
  HIDE_INVITE_MODAL,
  HIDE_PREVIEW_MODAL,
  HIDE_TIMELINE_MODAL,
  HIDE_WORKSPACE_MODAL,
  REMOVE_ARTICLE,
  SHOW_ARTICLE_MODAL,
  SHOW_FOLDER_MODAL,
  SHOW_INVITE_MODAL,
  SHOW_PREVIEW_MODAL,
  SHOW_TIMELINE_MODAL,
  SHOW_WORKSPACE_MODAL,
  SORT_FILES,
  TOGGLE_ARTICLE_VIEW_LOADING,
  TOGGLE_ARTICLES_LOADING,
  TOGGLE_FOLDERS_LOADING,
  TOGGLE_VIEW_LOADING,
  UPDATE_ARTICLE,
  UPDATE_TIMELINE_POST,
  WORKSPACE_DELETED,
} from './mutation-types';
import _ from 'lodash';

export default {
  /**
   * @param state
   * @param loading
   */
  [CHANGE_WORKSPACE_LOADING](state, loading) {
    state.loading = loading;
  },
  /**
   * @param state
   * @param workspace
   */
  [SHOW_WORKSPACE_MODAL](state, workspace) {
    state.showModal = true;
    state.modalWorkspace = workspace;
  },
  /**
   * @param state
   */
  [HIDE_WORKSPACE_MODAL](state) {
    state.showModal = false;
    state.modalWorkspace = null;
  },
  /**
   * @param state
   * @param data
   */
  [GET_WORKSPACES](state, data) {
    state.workspaces = data;
  },
  /**
   * @param state
   * @param id
   */
  [WORKSPACE_DELETED](state, id) {
    state.workspaces = state.workspaces.filter(w => w.id !== id)
  },
  /**
   * @param state
   * @param data
   */
  [GET_CURRENT_WORKSPACE](state, data) {
    state.view.workspace = data || {}
  },
  /**
   * @param state
   * @param data
   */
  [TOGGLE_VIEW_LOADING](state, data) {
    state.view.loading = data
  },

  /**
   *
   * @param state
   * @param data
   */
  [GET_ARTICLES](state, data) {
    state.view.articles.data = data;
  },
  /**
   *
   * @param state
   * @param data
   */
  [TOGGLE_ARTICLE_VIEW_LOADING](state, data) {
    state.view.articles.view.loading = data;
  },
  /**
   *
   * @param state
   * @param data
   */
  [GET_ARTICLE](state, data) {
    state.view.articles.view.article = data;
  },
  /**
   *
   * @param state
   * @param data
   */
  [TOGGLE_ARTICLES_LOADING](state, data) {
    state.view.articles.loading = data;
  },
  /**
   *
   * @param state
   * @param data
   */
  [SHOW_ARTICLE_MODAL](state, data) {
    state.view.articles.modal.show = true;
    state.view.articles.modal.object = _.cloneDeep(data.object);
  },
  /**
   *
   * @param state
   */
  [HIDE_ARTICLE_MODAL](state) {
    state.view.articles.modal.show = false;
    state.view.articles.modal.object = null;
  },
  /**
   *
   * @param state
   * @param data
   */
  [CREATE_ARTICLE](state, data) {
    state.view.articles.data.unshift(data);
  },
  /**
   *
   * @param state
   * @param article
   */
  [UPDATE_ARTICLE](state, article) {
    let index = state.view.articles.data.findIndex(a => a.id === article.id);
    state.view.articles.data[index] = {...state.view.articles.data[index], ...article};
    state.view.articles.data = [...state.view.articles.data];
  },
  /**
   *
   * @param state
   * @param id
   */
  [REMOVE_ARTICLE](state, id) {
    const rows = state.view.articles.data.filter(a => a.id !== id);
    state.view.articles.data = [...rows];
  },

  /**
   *
   * @param state
   * @param data
   */
  [SHOW_TIMELINE_MODAL](state, data) {
    state.view.timeline.modal.show = true;
    state.view.timeline.modal.object = _.cloneDeep(data);
  },
  /**
   *
   * @param state
   */
  [HIDE_TIMELINE_MODAL](state) {
    state.view.timeline.modal.show = false;
    state.view.timeline.modal.object = null;
  },
  /**
   *
   * @param state
   * @param data
   */
  [GET_TIMELINE_DATA](state, data) {
    state.view.timeline.loading = false;
    state.view.timeline.data = data;
  },
  /**
   *
   * @param state
   */
  [CHANGE_TIMELINE_LOADING](state) {
    state.view.timeline.loading = !state.view.timeline.loading;
  },
  /**
   *
   * @param state
   * @param data
   */
  [ADD_TIMELINE_POST](state, data) {
    state.view.timeline.data.unshift(data);
  },
  /**
   *
   * @param state
   * @param timeline
   */
  [UPDATE_TIMELINE_POST](state, timeline) {
    let index = state.view.timeline.data.findIndex(a => a.id === timeline.id);
    state.view.timeline.data[index] = {...state.view.timeline.data[index], ...timeline};
    state.view.timeline.data = [...state.view.timeline.data];
  },
  /**
   *
   * @param state
   * @param id
   */
  [DELETED_TIMELINE_POST](state, id) {
    state.view.timeline.data = state.view.timeline.data.filter(t => t.id !== id);
  },
  /**
   *
   * @param state
   * @param loading
   */
  [CHANGE_TIMELINE_MODAL_LOADING](state, loading) {
    state.view.timeline.modal.loading = loading
  },
  /**
   *
   * @param state
   * @param data
   */
  [GET_ATTACH_CONFIG](state, data) {
    state.view.folders.attachConfig = data
  },
  /**
   * @param state
   * @param { Object } data
   */
  [SHOW_FOLDER_MODAL](state, data) {
    state.view.folders.modal.show = true;
    state.view.folders.modal.object = data;
  },
  /**
   * @param state
   */
  [HIDE_FOLDER_MODAL](state) {
    state.view.folders.modal.show = false;
    state.view.folders.modal.object = null;
  },
  /**
   * @param state
   * @param data
   */
  [GET_ALL_FOLDERS](state, data) {
    state.view.folders.folderAndFiles = data;
  },
  /**
   * @param state
   * @param data
   */
  [TOGGLE_FOLDERS_LOADING](state, data) {
    state.view.folders.loading = data;
  },
  /**
   * @param state
   * @param breadcrumbData
   * @param folder
   */
  [GET_BREAD_CRUMB](state, {breadcrumbData, folder}) {
    state.view.folders.breadcrumb = breadcrumbData.map(bc => (
      {text: bc.name, to: {name: 'workspace.files', params: {folderId: bc.id}}}
    ));
    state.view.folders.breadcrumb.push({text: folder.name})
  },
  /**
   * @param state
   * @param ids
   */
  [FOLDER_DELETED](state, ids) {
    state.view.folders.folderAndFiles = state.view.folders.folderAndFiles.filter(a => !ids.includes(a.id))
  },
  /**
   *
   * @param state
   * @param data
   */
  [GET_CURRENT_FOLDER](state, data) {
    state.view.folders.folder = data || {}
  },
  /**
   *
   * @param state
   * @param data
   */
  [ADD_ATTACH_FILES](state, data) {
    const fileIds = state.view.folders.folderAndFiles.map(d => d.id);
    state.view.folders.folderAndFiles = state.view.folders.folderAndFiles
      .concat(data.filter(d => !fileIds.includes(d.id)))

  },
  /**
   *
   * @param state
   * @param column
   */
  [SORT_FILES](state, column) {
    state.view.folders.folderAndFiles = state.view.folders.folderAndFiles
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
  [SHOW_PREVIEW_MODAL](state, data) {
    state.view.folders.previewModal.show = true;
    state.view.folders.previewModal.files = data.files;
    state.view.folders.previewModal.activeFile = data.activeFile;
  },
  /**
   *
   * @param state
   */
  [HIDE_PREVIEW_MODAL](state) {
    state.view.folders.previewModal.show = false;
  },
  /**
   *
   * @param state
   * @param index
   */
  [CHANGE_CAROUSEL](state, index) {
    state.view.folders.previewModal.activeFile = index;
  },
  /**
   *
   * @param state
   */
  [SHOW_INVITE_MODAL](state) {
    state.view.invite.modal.show = true;
  },
  /**
   *
   * @param state
   */
  [HIDE_INVITE_MODAL](state) {
    state.view.invite.modal.show = false;
  },
  /**
   *
   * @param state
   * @param data
   */
  [GET_ALL_USER](state, data) {
    state.view.invite.modal.users = data
  },

};
