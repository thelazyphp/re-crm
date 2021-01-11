import isNull from './isNull'
import isArray from './isArray'
import isObject from './isObject'

/**
 * @param {*} value
 * @returns {boolean}
 */
export default function (value) {
  let isEmpty = false

  if (isNull(value) || value === '') {
    isEmpty = true
  } else if (value instanceof File && value.size === 0) {
    isEmpty = true
  } else if (isArray(value) && value.length === 0) {
    isEmpty = true
  } else if (isObject(value) && Object.keys(value).length === 0) {
    isEmpty = true
  }

  return isEmpty
}
