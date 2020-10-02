import {
  SHOW_TIMELINE_MODAL,
  SET_TIMELINE_DATA,
  SET_TIMELINE_LOADING,
  HIDE_TIMELINE_MODAL
} from './mutation-types'
import httpService from "@/core/services/httpService";

export function showTimelineModal({commit}) {
  commit(SHOW_TIMELINE_MODAL)
}

export function getData({commit}) {
  commit(SET_TIMELINE_LOADING, true);
  const {success, body} = httpService.get('/v1/workspaces/timeline?expand=timelinePost&sort=-created_at');
  if (success) {
    commit(SET_TIMELINE_DATA, body);
    commit(SET_TIMELINE_LOADING, false);
  }
  return body;
}

export function dropdownData() {

}