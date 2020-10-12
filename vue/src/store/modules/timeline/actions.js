import {
  SHOW_TIMELINE_MODAL,
  SET_TIMELINE_DATA,
  SET_TIMELINE_LOADING,
  HIDE_TIMELINE_MODAL,
  SET_DROPDOWN_DATA
} from './mutation-types'
import httpService from "@/core/services/httpService";

const url = '/v1/workspaces/timeline';

export function showTimelineModal({commit}) {
  commit(SHOW_TIMELINE_MODAL)
}

export function hideTimelineModal({commit}) {
  commit(HIDE_TIMELINE_MODAL)
}

export async function getTimelinePosts({commit}, workspaceId) {
  const res = await httpService.get(`${url}?workspace_id=${workspaceId}&sort=-created_at`);
  if (res.success) {
    commit(SET_TIMELINE_DATA, res.body);
  }
  return res;
}

export async function getDropdownData({commit}) {
  const {success, body} = await httpService.get(`/v1/workspaces/workspace/get-user-workspaces`);
  if (success) {
    commit(SET_DROPDOWN_DATA, body)
  }
}

export async function deleteTimelinePost({commit, dispatch}, id) {
  const {success} = await httpService.delete(`${url}/${id}`);
  if (success) {
    dispatch('getTimelinePosts');
  }
}

export async function updateTimelinePost({commit, dispatch}, data) {
  const {success} = await httpService.put(`${url}/${data.id}`, data);
  if (success) {
    dispatch('getTimelinePosts');
  }
}

export async function postOnTimeline({dispatch}, data) {
  const res = await httpService.post(`${url}?expand=workspace`, data);
  if (res.success) {
    dispatch('getTimelinePosts', data.workspaceId);
  }
  return res;
}