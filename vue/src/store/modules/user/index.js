import * as actions from './actions';
import mutations from './mutations';
import * as getters from './getters';
import state from './state';
import invitations from "./invitation/invitations";

export default {
  namespaced: true,
  modules: {
    invitations,
  },
  mutations,
  actions,
  state,
  getters,
};
