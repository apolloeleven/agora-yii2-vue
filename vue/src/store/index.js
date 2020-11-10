import Vuex from "vuex";
import Vue from 'vue';
import setup from './modules/setup';
import user from './modules/user';
import employee from './modules/employee'
import workspace from './modules/workspaces';
import article from './modules/workspaces/article';
import httpService from "../core/services/httpService";
import {SHARE_ARTICLE, SHARE_FILE} from "@/core/services/event-bus";

// Load vuex
Vue.use(Vuex);

// Create store
export default new Vuex.Store({
  state: {
    menuCollapsed: false,
    menuHidden: false,
    _menuItems: {},
  },
  getters: {
    menuItems: state => Object.values(state._menuItems).sort((a, b) => a.weight - b.weight),
    favourites: state => Object.values(state._menuItems).filter(item => item.favourite).sort((a, b) => a.weight - b.weight),
  },
  actions: {
    async initGlobals({dispatch}) {
      await dispatch('setup/getCountries');
    },
    addMenuItem({commit}, {name, menuItem}) {
      commit('addMenuItem', {name, menuItem});
    },
    removeMenuItem({commit}, path) {
      commit('removeMenuItem', path);
    },
    toggleMenuCollapse({commit, state}) {
      commit('toggleMenuCollapse', !state.menuCollapsed);
    },
    toggleMenuHide({commit, state}) {
      commit('toggleMenuHide', !state.menuHidden);
    },
    async shareToTimeline({commit, state}, data) {
      const res = await httpService.post(`/v1/workspaces/timeline`, data);
      if (res.success) {
        const currentArticle = state.article.currentArticle;
        if (currentArticle.id === data.article_id && data.action === SHARE_ARTICLE) {
          currentArticle.share_count++;
        } else if (data.action === SHARE_FILE) {
          state.article.articleFiles.filter(a => data.attachment_ids.includes(a.id)).forEach(item => item.share_count++);
        }
      }
      return res;
    },
  },
  mutations: {
    toggleMenuCollapse: (state, collapsed) => state.menuCollapsed = collapsed,
    toggleMenuHide: (state, hide) => state.menuHidden = hide,
    addMenuItem: (state, {name, menuItem}) => {
      state._menuItems = {
        ...state._menuItems,
        [name]: menuItem
      }
    },
    removeMenuItem: (state, name) => {
      Vue.delete(state._menuItems, name);
    },
  },
  modules: {
    setup,
    user,
    employee,
    workspace,
    article,
  }
});
