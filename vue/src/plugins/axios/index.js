import axios from 'axios';
import {AppSettings} from "../../shared/AppSettings";
import auth from './../../core/services/auth.service';
import router from "../../shared/router";
import Vue from "vue";

let config = {
  baseURL: AppSettings.url()
};

const axiosClient = axios.create(config);

axiosClient.interceptors.request.use(config => {
  if (auth.loggedIn()) {
    config.headers.Authorization = `Bearer ${auth.getToken()}`;
  }
  return config;
});

axiosClient.interceptors.response.use(
  response => {
    return response;
  },
  error => {
    if (error && error.response && error.response.status === 401) {
      router.push('/login')
    }
    return Promise.reject(error);
  }
);

export default axiosClient;

Vue.use({
  install(Vue) {
    Vue.prototype.$http = axiosClient;
  }
});
