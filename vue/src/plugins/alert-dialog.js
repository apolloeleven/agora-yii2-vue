import Vue from 'vue';
import i18n from "@/shared/i18n";

const ConfirmModal = {
  install(Vue) {
    Vue.prototype.$alert = function (message, title = i18n.t('Operation was not successful')) {
      return new Promise((resolve, reject) => {
        this.$bvModal.msgBoxOk(message, {
          title: title,
          okVariant: 'danger',
          headerBgVariant: 'danger',
          headerTextVariant: 'light',
          footerClass: 'p-2',
          hideHeaderClose: false,
          centered: true
        })
            .then(value => {
              resolve(value)
            })
            .catch(err => {
              reject(err);
            })
      })
    }
  }
};

Vue.use(ConfirmModal);
