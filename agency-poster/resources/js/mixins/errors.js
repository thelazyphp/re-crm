export default {
    data () {
        return {
            errors: {}
        }
    },

    methods: {
        clearErrors () {
            Object.keys(this.errors).forEach(key => {
                this.errors[key] = ''
            })
        },

        setErrors (errors) {
            Object.keys(errors).forEach(key => {
                Array.isArray(errors[key])
                    ? this.errors[key] = errors[key][0]
                    : this.errors[key] = errors[key]
            })
        },
    }
}
