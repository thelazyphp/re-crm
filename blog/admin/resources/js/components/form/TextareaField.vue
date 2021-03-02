<template>
    <base-field :field="field"
                :errors="errors">
        <textarea :id="field.attribute"
                  v-model="value"
                  v-bind="attributes"
                  class="form-control"
                  :disabled="field.readonly"
                  :aria-describedby="field.help ? `${field.attribute}-help` : null"></textarea>
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
                class: this.errorClass,
                rows: this.field.meta.rows || 3,
                placeholder: this.field.meta.placeholder || this.field.name
            };
        }
    }
};
</script>
