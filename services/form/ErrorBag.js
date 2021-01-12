class ErrorBag {
  /**
   * @type {object}
   */
  #errors = {}

  /**
   * @returns {boolean}
   */
  hasAny () {
    return Object.keys(this.#errors).length !== 0
  }

  /**
   * @param {string} field
   * @returns {boolean}
   */
  has (field) {
    return Object.keys(this.#errors).includes(field)
  }

  /**
   * @param {string} field
   * @returns {string[]}
   */
  get (field) {
    return this.has(field) ? this.#errors[field] : []
  }

  /**
   * @param {string} field
   * @param {string[]} messages
   * @returns {ErrorBag}
   */
  set (field, messages) {
    this.#errors[field] = messages
    return this
  }

  /**
   * @returns {ErrorBag}
   */
  clear () {
    this.#errors = {}
    return this
  }

  /**
   * @returns {string[]}
   */
  all () {
    const arr = []

    for (let field in this.#errors) {
      for (let message of this.#errors[field]) {
        arr.push(message)
      }
    }

    return arr
  }

  /**
   * @param {string} field
   * @returns {string?}
   */
  first (field) {
    return this.has(field) ? this.#errors[field][0] : null
  }
}

export default ErrorBag
