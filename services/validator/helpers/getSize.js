import isNumeric from './isNumeric'
import isArray from './isArray'
import isString from './isString'

/**
 * @param {*} value
 * @returns {number}
 */
export default function (value) {
  let size = 0

  if (isNumeric(value)) {
    size = Number(value)
  } else if (value instanceof File) {
    size = value.size
  } else if (isArray(value) || isString(value)) {
    size = value.length
  }

  return size
}
