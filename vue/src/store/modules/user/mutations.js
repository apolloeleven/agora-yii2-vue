import { SET_USER, SET_PROFILE_LOADING} from './mutation-types';

export default {
    /**
    *
    * @param { UserState } state
    * @param { array } userProfile
    */
    [SET_USER](state, userProfile) {
        state.userProfile.data = userProfile;
        state.userProfile.loaded = true;
    },

    /**
     *
     * @param { UserState } state
     * @param { boolean } loading
     */
    [SET_PROFILE_LOADING](state, loading) {
        state.userProfile.loading = loading;
    },
};
