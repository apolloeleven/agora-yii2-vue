import Vue from 'vue'
import App from './App.vue'

import './core/index';

import router from './shared/router';
import i18n from './shared/i18n';

import './modules';
import './plugins';
import store from './store';
import './index.scss';

import VueMoment from 'vue-moment';
import moment from 'moment-timezone'

Vue.use(VueMoment, {moment});
Vue.config.productionTip = false;

new Vue({
  i18n,
  store,
  router,
  render: h => h(App)
}).$mount('#app');
