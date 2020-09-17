import { SET_USER } from './mutation-types';

export default {
    /**
    *
    * @param { UserState } state
    * @param { object } user
    */
    [SET_USER](state, { user }) {
        state.user = user;
    },
};
