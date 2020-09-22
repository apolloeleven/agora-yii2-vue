import userService from "../../modules/User/userService";
import auth from "../../core/services/authService";
import {eventBus, USER_CREATED, USER_UPDATED} from "../../core/services/event-bus";
import store from "../index";

export default {
  namespaced: true,
  state: {
    readOnlyForm: {
      showModal: false,
      user: {}
    },
    deleteForm: {
      showModal: false,
      deletedUser: {}
    },
    userList: {
      showModal: false,
      modalUser: {},
      loading: false,
      users: [],
      pageCount: 1,
      page: 1,
      total: null,
      perPage: null,
      sort: 'name',
      pKeyword: '',
      keyword: '',
      name: '',
      email: '',
      phone: '',
      dropDown: {
        jobTitles: [],
        roles: [],
        countries: [],
        specialTasks: [],
        expertise: [],
        language: [],
      }
    },
    currentUser: {
      userProfile: {},
    },
    autoCompleteData: {
      departmentOptions: [],
      expertiseOptions: [],
      jobTitleOptions: [],
      specialTaskOptions: [],
      languageOptions: [],
      countryOptions: [],
      roleOptions: [],
    },
    attachmentConfig: {},
    defaultAvatar: '/assets/img/users/no_avatar.png',
  },
  getters: {
    //TODO permissions
  },
  actions: {
    /**
     * Get user own profile data
     *
     * @param commit
     * @returns {Promise<*>}
     */
    async getProfileData({commit}) {
      const response = await auth.getProfileData();

      if (response.success) {
        commit('getProfileData', response.body);
      }
    },

    showModal({commit}, payload = {}) {
      commit('showModal', payload);
    },

    hideModal({commit}, payload = {}) {
      commit('hideModal', payload);
    },

    /**
     * Get all user
     *
     * @param commit
     * @param state
     * @param params
     * @returns {Promise<void>}
     */
    async getAll({commit, state}, params = {}) {
      if (state.userList.keyword) {
        params.keyword = state.userList.keyword;
      }
      if (state.userList.name) {
        params.name = state.userList.name;
      }
      if (state.userList.email) {
        params.email = state.userList.email;
      }
      if (state.userList.phone) {
        params.phone = state.userList.phone;
      }

      let {keyword, name, email, phone, dropDown, perPage, page, sort} = {
        ...state.userList,
        ...params
      };
      commit('changeLoading');
      const res = await userService.get({keyword, name, email, phone, dropDown, perPage, page, sort});
      const {
        'x-pagination-current-page': _page,
        'x-pagination-page-count': pageCount,
        'x-pagination-per-page': _perPage,
        'x-pagination-total-count': total,
      } = res.response.headers;

      const {data} = res.response;

      commit('changeLoading');
      commit('getAll', {users: data, sort, page: _page, pageCount, perPage: _perPage, total});
    },

    async delete({commit}, payload) {
      const response = await userService.delete(payload);

      if (response.success) {
        commit('userDeleted', payload);
      }

      return response;
    },

    async update({dispatch}, payload) {
      const response = await userService.update(payload);

      if (response.success) {
        eventBus.$emit(USER_UPDATED, response.body);
        dispatch('getAll');
      }

      return response
    },

    addDepartmentOptions({commit}, payload) {
      commit('addDepartmentOptions', payload);
    },

    addLanguageOptions({commit}, payload) {
      commit('addLanguageOptions', payload);
    },

    addSpecialTaskOptions({commit}, payload) {
      commit('addSpecialTaskOptions', payload);
    },

    addExpertiseOptions({commit}, payload) {
      commit('addExpertiseOptions', payload);
    },

    logout({commit}) {
      store.commit('dashboard/resetDashboardData');
      commit('logout');
    },

    async showForm({commit}, user) {
      const response = await userService.getById(user.id);

      if (response.success) {
        commit('showForm', response.body);
      }
    },

    async showUserDeleteForm({commit}, user) {
      commit('showUserDeleteForm', user);
    },

    closeForm({commit}) {
      commit('closeForm');
    },

    closeDeleteForm({commit}) {
      commit('closeDeleteForm');
    },
  },
  mutations: {
    showModal: (state, user) => {
      state.userList.showModal = true;
      state.userList.modalUser = user;
    },

    hideModal: (state, user) => {
      state.userList.showModal = false;
      state.userList.modalUser = user;
    },

    showForm: (state, user) => {
      state.readOnlyForm.showModal = true;
      state.readOnlyForm.user = user;
    },

    showUserDeleteForm: (state, user) => {
      state.deleteForm.showModal = true;
      state.deleteForm.deletedUser = user;
    },

    closeForm: (state) => {
      state.readOnlyForm.showModal = false;
      state.readOnlyForm.user = {};
    },

    closeDeleteForm: (state) => {
      state.deleteForm.showModal = false;
    },

    getAll: (state, {users, sort, page, pageCount, perPage, total}) => {
      state.userList.users = users;
      state.userList.page = page;
      state.userList.pageCount = pageCount;
      state.userList.perPage = perPage;
      state.userList.total = total;
      state.userList.sort = sort;
    },

    changeLoading: (state) => state.userList.loading = !state.userList.loading,

    userDeleted: (state, user) => {
      const {userId} = user;
      state.userList.users = state.userList.users.filter(user => user.id !== userId);
    },

    addDepartmentOptions: (state, options) => {
      state.autoCompleteData.departmentOptions = [...new Set(state.autoCompleteData.departmentOptions.concat(options.value))];
    },

    addLanguageOptions: (state, options) => {
      state.autoCompleteData.languageOptions = [...new Set(state.autoCompleteData.languageOptions.concat(options.value))];
    },

    addSpecialTaskOptions: (state, options) => {
      state.autoCompleteData.specialTaskOptions = [...new Set(state.autoCompleteData.specialTaskOptions.concat(options.value))];
    },

    addExpertiseOptions: (state, options) => {
      state.autoCompleteData.expertiseOptions = [...new Set(state.autoCompleteData.expertiseOptions.concat(options.value))];
    },

    getProfileData: (state, profileData) => {
      state.currentUser = profileData.user;
      state.attachmentConfig = profileData.attachmentConfig;
    },

    setAutoCompleteData: (state, payload) => {
      state.autoCompleteData.languageOptions = payload.languages;
      state.autoCompleteData.jobTitleOptions = payload.jobTitles;
      state.autoCompleteData.specialTaskOptions = payload.specialTasks;
      state.autoCompleteData.departmentOptions = payload.departments;
      state.autoCompleteData.expertiseOptions = payload.expertises;
      state.autoCompleteData.countryOptions = payload.countries;
      state.autoCompleteData.roleOptions = payload.roles;
    },

    logout: state => {
      state.currentUser = {
        userProfile: {},
      };
    },
  }
}
