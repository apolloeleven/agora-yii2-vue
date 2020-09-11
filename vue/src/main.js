import Vue from 'vue'
import App from './App.vue'
import router from './shared/router';
import i18n from './shared/i18n';
import {FontAwesomeIcon} from '@fortawesome/vue-fontawesome';
import './modules';
import './plugins';
import './icons';
import store from './store';
import './index.scss';

Vue.use(require('vue-moment'));
Vue.component('font-awesome-icon', FontAwesomeIcon);

Vue.config.productionTip = false;

new Vue({
  i18n,
  store,
  router,
  render: h => h(App)
}).$mount('#app');
