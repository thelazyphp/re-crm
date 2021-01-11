import isNumber from './isNumber'
import isBigint from './isBigint'

/**
 * @param {*} value
 * @returns {boolean}
 */
export default function (value) {
  return isNumber(value) || isBigint(value) || isNumber(Number(value)) || isBigint(Number(value))
}
