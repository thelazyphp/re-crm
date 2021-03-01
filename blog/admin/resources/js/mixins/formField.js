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
        fill (data) {
            data[this.field.attribute] = this.value;
        },

        initValue () {
            if (this.field.value !== null && this.field.value !== undefined) {
                this.value = this.field.value;
            } else if (this.field.default !== null && this.field.default !== undefined) {
                this.value = this.field.default;
            }
        }
    }
};
