import employeesService from "@/modules/setup/employees/employeesService";
import httpService from "@/core/services/httpService";
import {
  SET_DATA,
  SET_MODAL_DROPDOWN_DATA,
  SHOW_EMPLOYEE_MODAL,
  CHANGE_LOADING,
  HIDE_MODAL,
  DELETED_USER,
} from "@/store/modules/employee/mutation-types";
import {ACTIVE_USER, INACTIVE_USER} from "../../../constants";

export function showEmployeeModal({commit}, payload) {
  commit(SHOW_EMPLOYEE_MODAL, payload);
}

export async function getData({commit}, payload) {
  commit(CHANGE_LOADING);
  const res = await employeesService.get(payload);
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