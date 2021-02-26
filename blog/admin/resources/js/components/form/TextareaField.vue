<template>
    <base-field :field="field">
        <textarea :id="fieldId"
                  v-model="value"
                  v-bind="attributes"
                  class="form-control"
                  :aria-describedby="!field.help ? null : `${fieldId}-help`"></textarea>
    </base-field>
</template>

<script>
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
        }
    },

    computed: {
        fieldId () {
            return `${this.field.attribute}-field`;
        },

        attributes () {
            return {
                rows: this.field.meta.rows || 3,
                disabled: this.field.readonly || false,
                placeholder: this.field.meta.placeholder || this.field.name
            };
        }
    },

    created () {
        this.value = this.field.value || this.field.default || '';
    },

    methods: {
        fill (data) {
            data.append(this.field.attribute, this.value);
        }
    }
};
</script>
