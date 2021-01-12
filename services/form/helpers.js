/**
 * @param {*} value
 * @returns {boolean}
 */
const isBoolean = (value) => {
  return [0, 1, '0', '1', true, false].includes(value)
}

/**
 * @param {*} value
 * @returns {boolean}
 */
const isArray = (value) => {
  return Array.isArray(value)
}

/**
 * @param {*} value
 * @returns {boolean}
 */
const isObject = (value) => {
  return typeof value === 'object'
}

/**
 * @param {*} value
 * @returns {boolean}
 */
const isString = (value) => {
  return typeof value === 'string'
}

/**
 * @param {*} value
 * @returns {boolean}
 */
const isNumber = (value) => {
  return typeof value === 'number'
}

/**
 * @param {*} value
 * @returns {boolean}
 */
const isBigint = (value) => {
  return typeof value === 'bigint'
}

/**
 * @param {*} value
 * @returns {boolean}
 */
const isNumeric = (value) => {
  return isNumber(value) ||
    isNumber(Number(value)) ||
    isBigint(value) ||
    isBigint(Number(value))
}

/**
 * @param {*} value
 * @returns {boolean}
 */
const isNull = (value) => {
  return value === null
}

/**
 * @param {*} value
 * @returns {boolean}
 */
const isEmpty = (value) => {
  return isNull(value) ||
    value === '' ||
    (isArray(value) && !value.length) ||
    (isObject(value) && !Object.keys(value).length)
}

/**
 * @param {*} value
 * @returns {boolean}
 */
const isNotEmpty = (value) => {
  return !isEmpty(value)
}

/**
 * @param {*} value
 * @returns {boolean}
 */
const isUndefined = (value) => {
  return typeof value === 'undefined'
}

export {
  isBoolean,
  isArray,
  isObject,
  isString,
  isNumber,
  isBigint,
  isNumeric,
  isNull,
  isEmpty,
  isNotEmpty,
  isUndefined,
}
