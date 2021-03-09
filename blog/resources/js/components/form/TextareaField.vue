<template>
    <base-field :field="field"
                :errors="errors">
        <textarea :id="field.attribute"
                  v-model="value"
                  v-bind="attributes"
                  class="form-control"
                  :aria-describedby="field.meta.help ? `${field.attribute}-help` : null"
                  :disabled="field.readonly"></textarea>
    </base-field>
</template>

<script>
import BaseField from './BaseField.vue';
import formField from '../../mixins/formField';
import formFieldErrors from '../../mixins/formFieldErrors';

export default {
    components: {
        BaseField,
    },

    mixins: [formField, formFieldErrors],

    computed: {
        attributes () {
            return {
                class: this.errorClass,
                rows: this.field.rows || 3,
                placeholder: this.field.placeholder || this.field.name,
                ...this.field.meta.attributes || {}
            };
        }
    }
};
</script>
