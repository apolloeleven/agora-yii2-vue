import {
  SHOW_TIMELINE_MODAL,
  SET_TIMELINE_DATA,
  SET_TIMELINE_LOADING,
  HIDE_TIMELINE_MODAL,
  SET_DROPDOWN_DATA
} from './mutation-types'
import httpService from "@/core/services/httpService";

export function showTimelineModal({commit}) {
  commit(SHOW_TIMELINE_MODAL)
}

export function hideTimelineModal({commit}) {
  commit(HIDE_TIMELINE_MODAL)
}

export async function getData({commit}) {
  commit(SET_TIMELINE_LOADING, true);
  const {success, body} = await httpService.get('/v1/workspaces/timeline?expand=timelinePost&sort=-created_at');
  if (success) {
    commit(SET_TIMELINE_DATA, body);
    commit(SET_TIMELINE_LOADING, false);
  }
  return body;
}

export async function getDropdownData({commit}) {
  const {success, body} = await httpService.get('/v1/workspaces/workspace/get-user-workspaces');
  if (success) {
    commit(SET_DROPDOWN_DATA, body)
  }
}

export async function deleteTimelinePost({commit, dispatch}, id) {
  const {success} = await httpService.delete(`/v1/workspaces/timeline/${id}`);
  if (success) {
    dispatch('getData');
  }
}

export async function updateTimelinePost({commit, dispatch}, data) {
  const {success} = await httpService.put(`/v1/workspaces/timeline/${data.id}`, data);
  if (success) {
    dispatch('getData');
  }
}