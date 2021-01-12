import rules from './rules'

import {
  isString,
  isNumeric,
  isNull,
  isEmpty,
  isUndefined,
} from './helpers'

import ErrorBag from './ErrorBag'

/**
 * @param {string} message
 * @param {string} attribute
 * @param {string?} rule
 * @param {string[]} [parameters]
 * @returns {string}
 */
const formatMessage = (message, attribute, rule = null, parameters = []) => {
  message = message.replace(':attribute', attribute)

  if (rule !== null) {
    switch (rule) {
      case 'min':
        if (isNumeric(parameters[0])) message = message.replace(':min', parameters[0])
        break
      case 'max':
        if (isNumeric(parameters[0])) message = message.replace(':max', parameters[0])
        break
    }
  }

  return message
}

class Validator {
  /**
   * @private
   * @type {ErrorBag}
   */
  #errors

  /**
   * @private
   * @type {object}
   */
  #data

  /**
   * @private
   * @type {object}
   */
  #rules

  /**
   * @private
   * @type {object}
   */
  #messages

  /**
   * @private
   * @type {object}
   */
  #customRules = {}

  /**
   * @constructor
   * @param {object} data
   * @param {object} rules
   * @param {object} [messages]
   * @returns {Validator}
   */
  constructor (data, rules, messages = {}) {
    this.#errors = new ErrorBag()
    this.#data = data
    this.#rules = rules
    this.#messages = messages
  }

  /**
   * @returns {ErrorBag}
   */
  errors () {
    return this.#errors
  }

  /**
   * @returns {boolean}
   */
  fails () {
    return this.errors().hasAny()
  }

  /**
   * @returns {boolean}
   */
  passes () {
    return !this.fails()
  }

  /**
   * @param {string} rule
   * @param {callback} callback
   * @returns {Validator}
   */
  extend (rule, callback) {
    this.#customRules[rule] = callback
    return this
  }

  /**
   * @returns {Validator}
   */
  validate () {
    const callbacks = Object.assign(rules, this.#customRules)
    this.errors().clear()

    for (let attribute in this.#rules) {
      let rules = this.#rules[attribute]

      if (isString(rules)) {
        rules = rules.split('|')
      }

      const value = this.#data[attribute]

      if (rules.includes('required')) {
        if (isUndefined(value) || isEmpty(value)) {
          this.errors().set(attribute, [
            formatMessage(this.#messages[`${attribute}.required`] || 'The :attribute field is required.', attribute)
          ])

          continue
        }
      }

      if (isUndefined(value) || rules.includes('nullable') && isNull(value)) {
        continue
      }

      const bail = rules.includes('bail')
      const messages = []

      for (let rule of rules) {
        let parameters = []
        if (rule.indexOf(':') !== -1) {
          const split = rule.split(':', 2)
          rule = split[0]
          parameters = split[1].split(',')
        }

        if (Object.keys(callbacks).includes(rule)) {
          if (!callbacks[rule]({ value, parameters, rules, data: this.#data, attribute })) {
            messages.push(formatMessage(this.#messages[`${attribute}.${rule}`] || 'The :attribute field is invalid.', attribute, rule, parameters))

            if (bail) {
              break
            }
          }
        }
      }

      if (messages.length) {
        this.errors().set(attribute, messages)
      }
    }

    return this
  }
}

export default Validator
