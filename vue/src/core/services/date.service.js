/**
 * Created by zura on 5/23/18.
 */
import moment from 'moment'

export const DATE_FORMATS = {
  USER_DATE_FORMAT: 'LL',
  USER_DATETIME_FORMAT: 'LLL'
}

export function convertDateForBackend (date) {
  return moment(date).format()
}
export function convertToUserDate (date) {
  return moment(date).format(DATE_FORMATS.USER_DATE_FORMAT)
}
