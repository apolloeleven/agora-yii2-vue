import Vue from 'vue';
import moment from 'moment';

Vue.filter('toDatetime', function (value) {
  if (!value) return '';
  return moment(value).format('YYYY-MM-DD HH:mm:ss');
});