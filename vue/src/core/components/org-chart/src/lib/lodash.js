import merge from 'lodash/merge'

export const mergeOptions = (obj, src) => {
  return merge(obj, src)
}
