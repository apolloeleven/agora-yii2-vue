import httpService from "@/core/services/httpService";
import {CURRENT_USER} from "@/constants";

export default {
  async getProfile(id) {
    let res = await httpService.get('/v1/setup/my-user/get-profile?id=' + id)
    if (res.success) {
      return res;
    }
  },

  async updateProfile(data) {
    const {email} = data;
    let res = await httpService.post('/v1/setup/my-user/update-profile', data)
    if (res.success) {
      this.updateCurrentUserEmail(email);
      return res;
    }
  },

  updateCurrentUserEmail(email) {
    let currentUser = JSON.parse(localStorage.getItem('CURRENT_USER'));
    currentUser.email = email;
    localStorage.setItem(CURRENT_USER, JSON.stringify(currentUser));
  }
}