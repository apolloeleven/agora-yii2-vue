import employeesService from "../../../modules/setup/employees/employeesService";
import httpService from "@/core/services/httpService";
import {
  SET_DATA,
  SET_MODAL_DROPDOWN_DATA,
  SHOW_EMPLOYEE_MODAL,
  CHANGE_LOADING,
  HIDE_MODAL,
  DELETED_USER,
  CHANGE_USER_ROLE,
  REMOVE_USER_FROM_WORKSPACE
} from "./mutation-types";
import {ACTIVE_USER, INACTIVE_USER} from "../../../constants";

export function showEmployeeModal({commit}, payload) {
  commit(SHOW_EMPLOYEE_MODAL, payload);
}

export async function getData({commit}, {workspaceId} = {}) {
  commit(CHANGE_LOADING);
  let res;
  if (workspaceId) {
    res = await employeesService.getByWorkspace(workspaceId);
  } else {
    res = await employeesService.get();
  }
  commit(CHANGE_LOADING);
  commit(SET_DATA, {rows: res.body});
}

export async function getModalDropdownData({commit}) {
  const res = await employeesService.getModalDropdownData();
  const response = await httpService.get('/v1/setup/countries', {
    params: {expand: 'departments',}
  });
  // TODO get countries from vuex setup module
  commit(SET_MODAL_DROPDOWN_DATA, {
    ...res.body,
    countries: response.body
  })
}

export function hideModal({commit}) {
  commit(HIDE_MODAL);
}

export async function deleteEmployee({commit}, data) {
  const res = await httpService.delete(`/v1/setup/employee/${data.id}`);
  if (res.success) {
    commit(DELETED_USER, data.id)
  }
  return res;
}

export async function updateUserStatus({commit}, data) {
  return httpService.put(`/v1/setup/employee/${data.id}`, {status: data.status ? INACTIVE_USER : ACTIVE_USER});
}

export async function changeRole({commit}, {userId, workspaceId, role}) {
  const {success} = await httpService.post(`/v1/setup/employee/change-role`, {userId, workspaceId, role});
  if (success) {
    commit(CHANGE_USER_ROLE, {userId, role})
  }
  return success;
}

export async function removeFromWorkspace({commit}, {workspaceId, userId}) {
  const {success} = await httpService.post(`/v1/setup/employee/remove-from-workspace`, {userId, workspaceId});
  if (success) {
    commit(REMOVE_USER_FROM_WORKSPACE, {userId})
  }
  return success;
}