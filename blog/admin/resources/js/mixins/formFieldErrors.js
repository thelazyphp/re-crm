import Errors from '../helpers/Errors';

export default {
    props: {
        errors: {
            type: Errors,

            default () {
                return new Errors();
            }
        }
    },

    computed: {
        errorClass () {
            return {
                'is-invalid': this.hasErrors
            };
        },

        hasErrors () {
            return this.errors.has(this.field.attribute);
        },

        firstError () {
            return this.errors.first(this.field.attribute);
        }
    }
};
