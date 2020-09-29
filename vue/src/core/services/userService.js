import httpService from "@/core/services/httpService";
import {CURRENT_USER} from "@/constants";

export default {

  prepareData(data) {
    if (data.image && data.image instanceof File) {
      const tmpData = new FormData();
      for (let key in data) {
        if (data.hasOwnProperty(key)) {
          tmpData.append(key, data[key] || '');
        }
      }
      data = tmpData;
    }
    return data;
  },

  updateCurrentUserEmail(email) {
    let currentUser = JSON.parse(localStorage.getItem('CURRENT_USER'));
    currentUser.email = email;
    localStorage.setItem(CURRENT_USER, JSON.stringify(currentUser));
  }
}