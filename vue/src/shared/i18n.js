import Vue from 'vue'
import VueI18n from 'vue-i18n';
import en from './translations/en.json'

Vue.use(VueI18n);

Vue.config.productionTip = false;

console.log(en);

export default new VueI18n({
  locale: 'en',
  fallbackLocale: 'es',
  messages: {en}
});
