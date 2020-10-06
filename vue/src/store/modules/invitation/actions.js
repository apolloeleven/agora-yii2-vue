import {SET_ALL, SHOW_INVITATION_MODAL, HIDE_MODAL, CHANGE_LOADING} from "@/store/modules/invitation/mutation-types";
import invitationService from "@/modules/User/Invitation/invitationService";

export async function getAll({commit}, keyword) {
  commit(CHANGE_LOADING);
  const res = await invitationService.get(keyword);
  commit(CHANGE_LOADING);
  commit(SET_ALL, {rows: res.body});
}

export function showInvitationModal({commit}) {
  commit(SHOW_INVITATION_MODAL);
}

export function hideModal({commit}) {
  commit(HIDE_MODAL);
}

export async function inviteUser({dispatch}, email) {
  const res = await invitationService.invite(email);
  if (res.success) {
    dispatch('getAll');
  }
  return res;
}

export async function deleteUser({dispatch}, id) {
  const res = await invitationService.delete(id);
  if (res.success) {
    dispatch('getAll');
  }
  return res;
}