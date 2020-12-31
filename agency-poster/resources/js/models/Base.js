export default class Base {
    constructor (data = {}) {
        return this.create(data, this.schema)
    }

    get schema () {
        return {}
    }

    create (data = {}, schema = {}) {
        Object.keys(schema).forEach(key => {
            const types = [
                'number',
                'string',
                'boolean',
                'object'
            ]

            const type = typeof data[key]

            ! [undefined, null].includes(data[key]) && types.includes(type)
                ? this[key] = data[key]
                : this[key] = schema[key]
        })

        return this
    }
}
