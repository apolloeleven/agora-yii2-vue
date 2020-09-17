import { SET_USER } from './mutation-types';

/**
*
* @param { function } commit
* @param { object } user
*/
export function setUser({ commit }, { user }) {
    commit(SET_USER, { user });
}
