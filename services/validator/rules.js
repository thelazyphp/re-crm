import getSize from './helpers/getSize'
import isArray from './helpers/isArray'
import isBigint from './helpers/isBigint'
import isBoolean from './helpers/isBoolean'
import isEmpty from './helpers/isEmpty'
import isNull from './helpers/isNull'
import isNumber from './helpers/isNumber'
import isNumeric from './helpers/isNumeric'
import isObject from './helpers/isObject'
import isString from './helpers/isString'
import isUndefined from './helpers/isUndefined'

export default {
  filled: ({ value }) => !isEmpty(value),
  regex: ({ value, parameters }) => isString(parameters[0]) && parameters[0].test(value),
  string: ({ value }) => isString(value),
  email: ({ value }) => (/^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/.test(value)),
  min: ({ value, parameters }) => isNumeric(parameters[0]) && getSize(value) >= Number(parameters[0]),
  max: ({ value, parameters }) => isNumeric(parameters[0]) && getSize(value) <= Number(parameters[0]),
  in: ({ value, parameters }) => parameters.includes(value),
  accepted: ({ value }) => ['yes', 'on', 1, true].includes(value),
  confirmed: ({ value, data, attribute }) => Object.keys(data).includes(`${attribute}_confirmation`) && value === data[`${attribute}_confirmation`]
}
