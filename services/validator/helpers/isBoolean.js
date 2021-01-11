/**
 * @param {*} value
 * @returns {boolean}
 */
export default function (value) {
  return [0, 1, '0', '1', true, false].includes(value)
}
