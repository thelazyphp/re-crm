import {
  isBoolean,
  isArray,
  isString,
  isNumeric,
  isNotEmpty,
} from './helpers'

/**
 * @param {*} value
 * @param {boolean} [numeric]
 * @returns {number}
 */
const size = (value, numeric = false) => {
  return numeric && isNumeric(value)
    ? Number(value)
    : ((isArray(value) || isString(value) ? value.length : 0))
}

/**
 * @type {object}
 */
export default {
  filled: ({ value }) => isNotEmpty(value),
  boolean: ({ value }) => isBoolean(value),
  string: ({ value }) => isString(value),
  numeric: ({ value }) => isNumeric(value),
  integer: ({ value }) => isNumeric(value) && new RegExp(/^-?[0-9]+$/).test(value),
  regex: ({ value, parameters }) => isString(parameters[0]) && new RegExp(parameters[0]).test(value),
  email: ({ value }) => new RegExp(/^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/).test(value),
  min: ({ value, parameters, rules }) => isNumeric(parameters[0]) && size(value, rules.includes('numeric', 'integer')) >= Number(parameters[0]),
  max: ({ value, parameters, rules }) => isNumeric(parameters[0]) && size(value, rules.includes('numeric', 'integer')) <= Number(parameters[0]),
  accepted: ({ value }) => ['yes', 'on', 1, true].includes(value),
  confirmed: ({ value, data, attribute }) => Object.keys(data).includes(`${attribute}_confirmation`) && value === data[`${attribute}_confirmation`],
}
