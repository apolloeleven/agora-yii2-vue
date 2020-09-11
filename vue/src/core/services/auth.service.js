import Vue from 'vue';
import {AppSettings} from "../../shared/AppSettings";

export default {

  loggedIn() {
    return !!sessionStorage.getItem('AUTH_TOKEN');
  },

  logout() {
    sessionStorage.removeItem('AUTH_TOKEN');
  },
  /**
   * @author Saiat Kalbiev <kalbievich11@gmail.com>
   * @returns {string}
   */
  getToken() {
    return sessionStorage.getItem('AUTH_TOKEN');
  },
  /**
   * @author Saiat Kalbiev <kalbievich11@gmail.com>
   * @param username
   * @param password
   * @returns {Promise<any>}
   */
  login(username, password) {
    return new Promise((resolve, reject) => {
      Vue.http.post(AppSettings.getUrl('/api/v1/users/auth/'), {
        username: username,
        password: password
      })
        .then(response => {
          sessionStorage.setItem('AUTH_TOKEN', response.data.token);
          sessionStorage.setItem('CURRENT_USER', JSON.stringify(response.data.user));
          resolve();
        }, error => {
          reject(error.data);
        });
    })
  },
  /**
   * @author Saiat Kalbiev <kalbievich11@gmail.com>
   * @returns {*}
   */
  getCurrentUser() {
    const userData = sessionStorage.getItem('CURRENT_USER');

    if (!userData) {
      return null;
    }

    return JSON.parse(userData);
  }
}
