import Vue from 'vue';
import i18n from "@/shared/i18n";

const ConfirmModal = {
  install(Vue) {
    Vue.prototype.$confirm = function (message, title = i18n.t('Please Confirm')) {
      return new Promise((resolve, reject) => {
        this.$bvModal.msgBoxConfirm(message, {
          title: title,
          buttonSize: 'sm',
          okVariant: 'danger',
          okTitle: 'OK',
          cancelTitle: 'Cancel',
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
