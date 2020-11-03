import Vue from 'vue';

Vue.filter('prettyBytes', function (num) {
  if (typeof num !== 'number' || isNaN(num)) {
    throw new TypeError('Expected a number');
  }

  let exponent;
  let unit;
  let neg = num < 0;
  let units = ['B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];

  if (neg) {
    num = -num;
  }

  if (num < 1) {
    return (neg ? '-' : '') + num + ' B';
  }

  exponent = Math.min(Math.floor(Math.log(num) / Math.log(1000)), units.length - 1);
  num = (num / Math.pow(1000, exponent)).toFixed(1) * 1;
  unit = units[exponent];

  return (neg ? '-' : '') + num + ' ' + unit;
});