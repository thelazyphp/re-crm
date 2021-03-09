export default {
    props: {
        field: {
            type: Object,
            required: true
        }
    },

    data () {
        return {
            value: ''
        };
    },

    created () {
        this.initValue();
        this.field.fill = this.fill;
    },

    methods: {
        /**
         * @param {FormData} data
         */
        fill (data) {
            data.append(
                this.field.attribute, this.value
            );
        },

        initValue () {
            this.value = this.field.value === null || this.field.value === undefined
                ? ''
                : this.field.value;
        }
    }
};
