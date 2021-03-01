<template>
    <base-field :field="field"
                :errors="errors">
        <select :id="field.attribute"
                v-model="value"
                v-bind="attributes"
                class="form-select"
                :readonly="field.readonly"
                :aria-describedby="field.help ? `${field.attribute}-help` : null">
            <option value=""
                    :disabled="!field.nullable">
                {{ placeholder }}
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
import BaseField from './BaseField.vue';
import formField from '../../mixins/formField';
import formFieldErrors from '../../mixins/formFieldErrors';

export default {
    mixins: [formField, formFieldErrors],

    components: {
        BaseField,
    },

    computed: {
        attributes () {
            return {
                class: this.errorClass
            };
        },

        placeholder () {
            return this.field.meta.placeholder || 'Choose an option...';
        }
    }
};
</script>
