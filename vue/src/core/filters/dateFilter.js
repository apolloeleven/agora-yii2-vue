import Vue from 'vue';
import moment from 'moment';
import i18n from "../../shared/i18n";

Vue.filter('toDatetime', function (value) {
  if (!value) return '';
  return moment(value).format('YYYY-MM-DD HH:mm:ss');
});

Vue.filter('relativeDate', function (value) {
  if (!value) return '';
  moment.locale(i18n.locale);
  return moment.tz(value, 'Asia/Tbilisi').fromNow()
});