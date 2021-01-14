/**
 * @param {*} value
 * @returns {boolean}
 */
function isString (value) {
  return typeof value === 'string'
}

/**
 * @param {*} value
 * @returns {boolean}
 */
function isNumber (value) {
  return typeof value === 'number'
}

/**
 * @param {*} value
 * @returns {boolean}
 */
function isNumeric (value) {
  return isNumber(value) ||
    isFinite(value) &&
    !isNaN(parseFloat(value))
}

/**
 * @param {*} value
 * @returns {boolean}
 */
function isUndefined (value) {
  return typeof value === 'undefined'
}

/**
 * @param {*} value
 * @returns {boolean}
 */
function isNull (value) {
  return value === null
}

/**
 * @param {*} value
 * @returns {boolean}
 */
function isArray (value) {
  return Array.isArray(value)
}

/**
 * @param {*} value
 * @returns {boolean}
 */
function isObject (value) {
  return typeof value === 'object'
}

/**
 * @param {*} value
 * @returns {boolean}
 */
function isEmpty (value) {
  return isNull(value) ||
    value === '' ||
    (isArray(value) && value.length === 0) ||
    (isObject(value) && Object.keys(value).length === 0)
}

/**
 * @param {*} value
 * @param {boolean} [numeric]
 * @returns {number}
 */
function size (value, numeric = false) {
  return numeric && isNumeric(value)
    ? Number(value)
    : ((isArray(value) || isString(value)) ? value.length : 0)
}

/**
 * @type {object}
 */
const rules = {
  string: ({ value }) => isString(value),
  numeric: ({ value }) => isNumeric(value),
  integer: ({ value }) => isNumeric(value) && /^-?[0-9]+$/.test(String(value)),
  email: ({ value }) => /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/.test(value),
  min: ({ value, parameters, rules }) => isNumeric(parameters[0]) && size(value, Object.keys(rules).includes['numeric', 'integer']) >= Number(parameters[0]),
  max: ({ value, parameters, rules }) => isNumeric(parameters[0]) && size(value, Object.keys(rules).includes['numeric', 'integer']) <= Number(parameters[0]),
  accepted: ({ value }) => ['yes', 'on', 1, true].includes(value),
  confirmed: ({ value, data, attribute }) => Object.keys(data).includes(`${attribute}_confirmation`) && value === data[`${attribute}_confirmation`],
}

class Errors {
  /**
   * @param {object} errors
   */
  constructor (errors) {
    this.errors = errors
  }

  /**
   * @returns {boolean}
   */
  any () {
    return Object.keys(this.errors).some(key => {
      return this.errors[key].length > 0
    })
  }

  /**
   * @returns {string[]}
   */
  all () {
    const arr = []

    for (const key in this.errors) {
      for (const message of this.errors[key]) {
        arr.push(message)
      }
    }

    return arr
  }

  /**
   * @param {string} field
   * @returns {boolean}
   */
  has (field) {
    return this.errors[field].length > 0
  }

  clear () {
    for (const key in this.errors) {
      this.errors[key] = []
    }
  }

  /**
   * @param {string} field
   * @param {string[]} message
   */
  add (field, message) {
    this.errors[field].push(message)
  }

  /**
   * @param {string} field
   * @returns {string[]}
   */
  get (field) {
    return this.errors[field]
  }

  /**
   * @param {string} field
   * @returns {string?}
   */
  first (field) {
    return this.has(field) ? this.errors[field][0] : null
  }
}

/**
 * @param {string} message
 * @param {string} attribute
 * @param {string?} rule
 * @param {string[]?} parameters
 */
const formatMessage = (message, attribute, rule = null, parameters = null) => {
  message = message.replace(':attribute', attribute)

  switch (rule) {
    case 'min':
      if (isNumeric(parameters[0])) message = message.replace(':min', parameters[0])
      break
    case 'max':
      if (isNumeric(parameters[0])) message = message.replace(':max', parameters[0])
      break
  }

  return message
}

class Form {
  /**
   * @type {object}
   */
  #rules = {}

  /**
   * @type {object}
   */
  #messages = {}

  /**
   * @type {object}
   */
  #customRules = {}

  /**
   * @param {object} data
   */
  constructor (data) {
    let errors = {}
    for (const key in data) {
      errors[key] = []
    }

    this.errors = new Errors(errors)
    this.loading = false
    this.data = data
    this.initialData = Object.assign({}, data)
  }

  reset () {
    Object.assign(this.data, this.initialData)
  }

  /**
   * @param {object} rules
   * @returns {Form}
   */
  rules (rules) {
    this.#rules = rules
    return this
  }

  /**
   * @param {object} messages
   * @returns {Form}
   */
  messages (messages) {
    this.#messages = messages
    return this
  }

  /**
   * @param {string} rule
   * @param {callback} callback
   * @returns {Form}
   */
  customRule (rule, callback) {
    this.#customRules[rule] = callback
    return this
  }

  /**
   * @returns {boolean}
   */
  fails () {
    return this.errors.any()
  }

  /**
   * @returns {boolean}
   */
  passes () {
    return !this.fails()
  }

  /**
   * @returns {Form}
   */
  validate () {
    const callbacks = Object.assign(rules, this.#customRules)
    this.errors.clear()

    for (const attribute in this.#rules) {
      let rules = this.#rules[attribute]

      if (isString(rules)) {
        rules = rules.split('|')
      }

      const value = this.data[attribute]

      if (rules.includes('required')) {
        if (isUndefined(value) || isEmpty(value)) {
          this.errors.add(attribute, formatMessage(this.#messages[`${attribute}.required`] || 'The :attribute field is required.', attribute))
          continue
        }
      }

      if (isUndefined(value) || rules.includes('nullable') && isNull(value)) {
        continue
      }

      const bail = rules.includes('bail')

      for (let rule of rules) {
        let parameters = []
        if (rule.indexOf(':') !== -1) {
          const arr = rule.split(':', 2)
          rule = arr[0]
          parameters = arr[1].split(',')
        }

        if (Object.keys(callbacks).includes(rule)) {
          if (!callbacks[rule]({ value, parameters, rules, data: this.data, attribute })) {
            this.errors.add(attribute, formatMessage(this.#messages[`${attribute}.${rule}`] || 'The :attribute field is invalid.', attribute, rule, parameters))

            if (bail) {
              break
            }
          }
        }
      }
    }

    return this
  }
}

export default Form
