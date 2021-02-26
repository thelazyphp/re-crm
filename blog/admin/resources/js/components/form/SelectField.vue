<template>
    <base-field :field="field">
        <select :id="fieldId"
                v-model="value"
                v-bind="attributes"
                class="form-control"
                :aria-describedby="!field.help ? null : `${fieldId}-help`">
            <option :value="null"
                    :disabled="!field.nullable">
                {{ field.meta.placeholder || 'Choose an option...' }}
            </option>
            <option v-for="(option, index) in field.options"
                    :key="index"
                    :value="option.value">
                {{ option.label }}
            </option>
        </select>
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
            value: null
        }
    },

    computed: {
        fieldId () {
            return `${this.field.attribute}-field`;
        },

        attributes () {
            return {
                disabled: this.field.readonly || false
            };
        }
    },

    created () {
        this.value = this.field.value || this.field.default || null;
    },

    methods: {
        fill (data) {
            data.append(this.field.attribute, this.value);
        }
    }
};
</script>
