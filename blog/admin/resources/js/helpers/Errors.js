export default class Errors {
    /**
     * @param {object} [errors]
     */
    constructor (errors = {}) {
        this.errors = errors;
    }

    clear () {
        this.errors = {};
    }

    /**
     * @param {string} attribute
     * @returns {boolean}
     */
    has (attribute) {
        return Object.keys(this.errors).includes(attribute);
    }

    /**
     * @param {string} attribute
     * @returns {string?}
     */
    first (attribute) {
        return this.has(attribute) ? this.errors[attribute][0] : null;
    }
};
