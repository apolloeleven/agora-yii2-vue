import {SET_USER} from './mutation-types';
import httpService from "../../../core/services/httpService";

/**
 *
 * @param { function } commit
 * @param { object } user
 */
export function setUser({commit}, {user}) {
  commit(SET_USER, {user});
}

/**
 *
 * @param { function } commit
 * @param { object } user
 */
export async function updateUser({commit}, {user}) {
  const response = await httpService.put(`/v1/user/update`, user)
  commit(SET_USER, {user});
}
