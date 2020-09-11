import Vue from 'vue'
import VueI18n from 'vue-i18n';

Vue.use(VueI18n);

Vue.config.productionTip = false;

export default new VueI18n({
  locale: 'en',
  fallbackLocale: 'es',
  messages: {
    en: {
      Name: 'Name'
    }
  }
});
