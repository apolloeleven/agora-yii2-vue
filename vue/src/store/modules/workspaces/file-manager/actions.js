import {
  FOLDER_DELETED,
  GET_ALL_FOLDERS,
  GET_ATTACH_CONFIG,
  GET_CURRENT_FOLDER,
  GET_FILES,
  HIDE_EDIT_LABEL_DIALOG,
  HIDE_FOLDER_MODAL,
  SHOW_EDIT_LABEL_DIALOG,
  SHOW_FOLDER_MODAL,
  START_LOADING,
  STOP_LOADING,
  UPDATE_LABEL,
} from './mutation-types';
import httpService from "../../../../core/services/httpService";

const url = '/v1/workspaces/folder';

/**
 * Show article form's modal
 *
 * @param commit
 * @param data
 */
export function showFolderModal({commit}, data) {
  commit(SHOW_FOLDER_MODAL, data);
}

/**
 * Hide article form's modal
 *
 * @param { function } commit
 * @param { bool } hideModal
 */
export function hideFolderModal({commit}, hideModal) {
  commit(HIDE_FOLDER_MODAL, hideModal);
}

/**
 * @param payload
 * @returns {Promise<unknown>}
 * @param { Object } data
 */
export async function createFolder({dispatch}, data) {
  return await httpService.post(url, prepareData(data));
}


/**
 * @param dispatch
 * @param { Object } data
 * @returns {Promise<unknown>}
 */
export async function updateFolder({dispatch}, data) {
  const id = data.id;
  data = prepareData(data);

  if (data instanceof FormData) {
    data.append('_method', 'PUT');
    return await httpService.post(`${url}/${id}`, data);
  } else {
    return await httpService.put(`${url}/${id}`, data);
  }
}

/**
 * @param commit
 * @param { Object } data
 * @returns {Promise<unknown>}
 */
export async function deleteFolder({commit}, data) {
  const res = await httpService.delete(`${url}/${data.id}`);
  if (res.success) {
    commit(FOLDER_DELETED, data.id)
  }
  return res;
}

/**
 * Get current article
 *
 * @param commit
 * @param { int } folderId
 * @returns {Promise<void>}
 */
export async function getCurrentFolder({commit}, folderId) {
  const {success, body} = await httpService.get(`${url}/${folderId}?expand=workspace,createdBy`)
  if (success) {
    commit(GET_CURRENT_FOLDER, body);
  }
}

// /**
//  *
//  * @param commit
//  * @param { Object } article
//  * @returns {Promise<void>}
//  */
// export async function destroyCurrentArticle({commit}, article) {
//   commit(GET_CURRENT_FOLDER, article)
// }
//
/**
 * Get articles for workspace view
 *
 * @param commit
 * @param { int } workspaceId
 * @returns {Promise<void>}
 */
export async function getFoldersByWorkspace({commit}, workspaceId) {
  const {success, body} = await httpService.get(`${url}?workspace_id=${workspaceId}&sort=title`)
  if (success) {
    commit(GET_ALL_FOLDERS, body)
    commit(START_LOADING)
  }
  commit(STOP_LOADING)
}

/**
 * Get articles by parent id for article view page
 *
 * @param commit
 * @param { int } parentId
 * @returns {Promise<void>}
 */
export async function getFoldersByParent({commit}, parentId) {
  const {success, body} = await httpService.get(`${url}/by-parent?folderId=${parentId}&sort=title`)
  if (success) {
    commit(GET_ALL_FOLDERS, body)
    commit(START_LOADING)
  }
  commit(STOP_LOADING)
}

/**
 * Prepare data for upload
 *
 * @param data
 * @returns {*}
 */
export function prepareData(data) {
  if (data.file && data.file instanceof File) {
    const tmp = new FormData();
    for (let key in data) {
      if (data.hasOwnProperty(key)) {
        if (key === 'depth' || key === 'is_file') {
          tmp.append(key, data[key]);
        } else {
          tmp.append(key, data[key] || '');
        }
      }
    }
    data = tmp;
  }
  return data;
}

//
// /** Article Files Actions */
//
// /**
//  * Get attachment config data
//  *
//  * @param commit
//  * @returns {Promise<void>}
//  */
// export async function getAttachConfig({commit}) {
//   const {success, body} = await httpService.get(`${url}/get-attach-config`)
//   if (success) {
//     commit(GET_ATTACH_CONFIG, body)
//   }
// }
//
// /**
//  * Upload files
//  *
//  * @param dispatch
//  * @param payload
//  * @param data
//  * @param config
//  * @returns {Promise<unknown>}
//  */
// export async function attachFiles({dispatch}, {data, config}) {
//   const res = await httpService.post(`${url}/attach-files`, prepareFiles(data), config)
//   if (res.success) {
//     dispatch('getFilesByArticle', data.article_id);
//   }
//   return res;
// }
//
// /**
//  * Get article files data by article
//  *
//  * @param commit
//  * @param articleId
//  * @returns {Promise<void>}
//  */
// export async function getFilesByArticle({commit}, articleId) {
//   const {success, body} = await httpService.get(url, {
//     params: {
//       articleId,
//       expand: 'updatedBy',
//       sort: 'name'
//     }
//   });
//   if (success) {
//     commit(GET_FILES, body);
//   }
// }
//
// /**
//  * Show edit label dialog form
//  *
//  * @param commit
//  * @param file
//  */
// export function showEditLabelDialog({commit}, file) {
//   commit(SHOW_EDIT_LABEL_DIALOG, file);
// }
//
// /**
//  * Hide edit label dialog form
//  *
//  * @param commit
//  */
// export function hideEditLabelDialog({commit}) {
//   commit(HIDE_EDIT_LABEL_DIALOG);
// }
//
// /**
//  * Update files label
//  *
//  * @param commit
//  * @param id
//  * @param label
//  * @returns {Promise<unknown>}
//  */
// export async function updateLabel({commit}, {id, label}) {
//   const res = await httpService.post(`${url}/change-label`, {id, label})
//   if (res.success) {
//     commit(UPDATE_LABEL, {id, label})
//   }
//   return res;
// }
//
// /**
//  * Delete attachments
//  *
//  * @param dispatch
//  * @param data
//  * @returns {Promise<void>}
//  */
// export async function deleteAttachments({dispatch}, data) {
//   const res = await httpService.post(`${url}/delete-attachments`, data)
//   if (res.success) {
//     dispatch('getFilesByArticle', data.article_id);
//     dispatch('hidePreviewModal');
//   }
//   return res;
// }
//
// /**
//  * Prepare file to upload
//  *
//  * @param data
//  * @returns {FormData}
//  */
// export function prepareFiles(data) {
//   const tmp = new FormData();
//   for (let key in data.files) {
//     if (data.files.hasOwnProperty(key)) {
//       tmp.append('files[]', data.files[key], data.files.name);
//     }
//   }
//   for (let key in data) {
//     if (data.hasOwnProperty(key) && data[key] !== 'files') {
//       tmp.append(key, data[key]);
//     }
//   }
//   data = tmp;
//   return data;
// }