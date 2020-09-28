import {SET_PROFILE_LOADING, SET_USER} from "@/store/modules/user/mutation-types";
import httpService from "@/core/services/httpService";

/**
 *
 * @param { function } commit
 * @param { object } user
 */
export async function getProfile({commit}) {
  commit(SET_PROFILE_LOADING, true);
  let response = await httpService.get('/v1/setup/my-user/profile');
  if (response.success) {
    commit(SET_USER, response.body);
    commit(SET_PROFILE_LOADING, false);
  }
  return response;
}

/**
 *
 * @param { function } commit
 * @param { any } user
 */
export async function updateProfile({commit}, user) {
  commit(SET_PROFILE_LOADING, true);
  const data = prepareData(user);
  data.append('_method', 'put');
  let {success, body} = await httpService.post('/v1/setup/my-user/update-profile', data)
  if (success) {
    commit(SET_USER, body);
    commit(SET_PROFILE_LOADING, false);
  }
}


function prepareData(data) {
  if (data.image && data.image instanceof File) {
    const tmpData = new FormData();
    for (let key in data) {
      if (data.hasOwnProperty(key)) {
        tmpData.append(key, data[key] || '');
      }
    }
    data = tmpData;
  }
  return data;
}