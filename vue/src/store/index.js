import Vuex from "vuex";
import Vue from 'vue';
import invitations from "./modules/invitations";

// Load vuex
Vue.use(Vuex);

// Create store
export default new Vuex.Store({
  state: {
    count: 0,
    menuCollapsed: false,
    menuHidden: false
  },
  actions: {
    toggleMenuCollapse({commit, state}) {
      commit('toggleMenuCollapse', !state.menuCollapsed);
    },
    toggleMenuHide({commit, state}) {
      commit('toggleMenuHide', !state.menuHidden);
    }
  },
  mutations: {
    toggleMenuCollapse: (state, collapsed) => state.menuCollapsed = collapsed,
    toggleMenuHide: (state, hide) => state.menuHidden = hide,
  },

  modules: {
    invitations,
  },
});
