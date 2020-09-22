import Vue from 'vue';
import BootstrapVue from './bootstrap-vue';
import i18n from "../shared/i18n";

const vueBootstrap = new (Vue.extend(BootstrapVue));

function getVariant(variant) {
  const availableVariants = ['secondary', 'primary', 'danger', 'warning', 'success', 'info', 'default'];
  return availableVariants.includes(variant) ? variant : 'default';
}

function getTitle(variant) {
  if (variant === 'success') {
    return i18n.t('Success');
  } else if (variant === 'danger') {
    return i18n.t('Error')
  } else {
    return i18n.t('Info')
  }
}

const Toaster = {
  install(Vue) {
    Vue.prototype.$toast = (message, variant = 'success') => {
      vueBootstrap.$bvToast.toast(message, {
        title: getTitle(variant),
        autoHideDelay: 5000,
        appendToast: false,
        variant: getVariant(variant)
      });
    }
  }
};

Vue.use(Toaster);

export default Toaster;