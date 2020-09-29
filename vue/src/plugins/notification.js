import Vue from 'vue'
import Notifications from 'vue-notification'
import i18n from "@/shared/i18n";

Vue.use(Notifications);


const SuccessNotification = {
  install(Vue) {
    Vue.prototype.$successToast = function (message, title = i18n.t('Success')) {
        this.$notify({
          group: 'success',
          type: 'success',
          title,
          text: message,
          duration: 1000000
        });
    }
  }
};

Vue.use(SuccessNotification);
