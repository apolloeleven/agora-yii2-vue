import httpService from "./httpService";
import {AUTH_TOKEN, CURRENT_USER, REDIRECT_TO} from "../../const";

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
    let res = await httpService.post('/user/login', data);

    if (res.success) {
      this.setToken(res.body.access_token);
      this.setCurrentUser(res.body);
    }

    return res;
  },

  /**
   * @param email
   * @returns {Promise<any>}
   */
  async resetPassword(email) {
    return await httpService.post('/user/reset-password', email);
  },
}