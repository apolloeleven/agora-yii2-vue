import httpService from "./httpService";
import {AUTH_TOKEN, CURRENT_USER, REDIRECT_TO} from "../../constants";
import router from "@/shared/router";

export default {

  getRedirectTo() {
    return localStorage.getItem(REDIRECT_TO);
  },

  removeRedirectTo() {
    localStorage.removeItem(REDIRECT_TO);
  },

  loggedIn() {
    return !!localStorage.getItem(AUTH_TOKEN);
  },

  logout() {
    localStorage.removeItem(AUTH_TOKEN);
    localStorage.removeItem(CURRENT_USER);
    router.push({name: 'auth.login'});
  },

  /**
   * @returns {string}
   */
  getToken() {
    return localStorage.getItem(AUTH_TOKEN);
  },

  /**
   * @param token
   */
  setToken(token) {
    localStorage.setItem(AUTH_TOKEN, token);
  },

  /**
   * @returns {*}
   */
  getCurrentUser() {
    const userData = localStorage.getItem(CURRENT_USER);

    if (!userData) {
      return null;
    }

    return JSON.parse(userData);
  },

  /**
   * @param userData
   * @returns {null}
   */
  setCurrentUser(userData) {
    if (!userData) {
      return null;
    }
    localStorage.setItem(CURRENT_USER, JSON.stringify(userData));
  },

  /**
   * @returns {Promise<any>}
   * @param data
   */
  async login(data) {
    let res = await httpService.post('v1/users/auth/login', data);

    if (res.success) {
      this.setToken(res.body.access_token);
      this.setCurrentUser(res.body);
    }

    return res;
  },

  /**
   * @param data
   * @returns {Promise<any>}
   */
  async register(data) {
    return await httpService.post('v1/users/auth/signup', data);
  },

  /**
   * @param email
   * @returns {Promise<any>}
   */
  async resetPasswordLink(email) {
    return await httpService.post('v1/users/auth/send-password-reset-link', email);
  },

  /**
   * @returns {Promise<any>}
   * @param data
   */
  async passwordReset(data) {
    return await httpService.post('v1/users/auth/password-reset', data);
  },

  /**
   * @param token
   * @returns {Promise<unknown>}
   */
  async checkToken(token) {
    return await httpService.get('v1/users/auth/check-token-validity?token=' + token);
  },
}