import {
  SHOW_TIMELINE_MODAL,
  GET_TIMELINE_DATA,
  CHANGE_LOADING,
  HIDE_TIMELINE_MODAL,
  DELETED_TIMELINE_POST,
} from './mutation-types'
import httpService from "@/core/services/httpService";

const url = '/v1/workspaces/timeline';

export function showTimelineModal({commit}, data) {
  commit(SHOW_TIMELINE_MODAL, data)
}

export function hideTimelineModal({commit}) {
  commit(HIDE_TIMELINE_MODAL)
}

export async function getTimelinePosts({commit}, workspaceId) {
  commit(CHANGE_LOADING)
  const res = await httpService.get(`${url}?workspace_id=${workspaceId}&expand=workspaceTimelinePosts,article,createdBy,
   timelineComments.createdBy,timelineComments.childrenComments.createdBy,timelineComments.childrenComments.parent,
   userLikes,myLikes&sort=-created_at`);
  if (res.success) {
    commit(CHANGE_LOADING)
    commit(GET_TIMELINE_DATA, res.body);
  }
  return res;
}

export async function deleteTimelinePost({commit}, data) {
  const res = await httpService.delete(`${url}/${data.id}`);
  if (res.success) {
    commit(DELETED_TIMELINE_POST, data.id);
  }
  return res;
}

export async function updateTimelinePost({commit, dispatch}, data) {
  const res = await httpService.put(`${url}/${data.id}`, data);
  if (res.success) {
    dispatch('getTimelinePosts', data.workspace_id);
  }
  return res;
}

export async function postOnTimeline({dispatch}, {data, config}) {
  const res = await httpService.post(`${url}?expand=workspaceTimelinePosts`, prepareData(data), config);
  if (res.success) {
    dispatch('getTimelinePosts', data.workspace_id);
  }
  return res;
}

export function prepareData(data) {
  if (data.file && data.file instanceof File) {
    const tmp = new FormData();
    for (let key in data) {
      if (data.hasOwnProperty(key)) {
        tmp.append(key, data[key] || '');
      }
    }
    data = tmp;
  }
  return data;
}