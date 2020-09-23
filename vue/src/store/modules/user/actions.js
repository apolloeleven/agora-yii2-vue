import httpService from "../../../core/services/userService";
import {SET_PROFILE_LOADING, SET_USER} from "@/store/modules/user/mutation-types";

/**
 *
 * @param { function } commit
 * @param { object } user
 */
export async function getProfile({commit}) {
  const user = JSON.parse(localStorage.getItem('CURRENT_USER'));
  commit(SET_PROFILE_LOADING, true);
  const {success, body} = await httpService.getProfile(user.id);
  if(success) {
    commit(SET_USER, body);
    commit(SET_PROFILE_LOADING, false);
  }
}

/**
 *
 * @param { function } commit
 * @param { any } user
 */
export async function updateProfile({commit}, user) {
  commit(SET_PROFILE_LOADING, true);
  const {success, body} = await httpService.updateProfile(user)
  if(success) {
    commit(SET_USER, body);
    commit(SET_PROFILE_LOADING, false);
  }
}
