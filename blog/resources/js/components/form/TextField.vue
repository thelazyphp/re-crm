<template>
    <base-field :field="field"
                :errors="errors">
        <input :id="field.attribute"
               v-model="value"
               v-bind="attributes"
               class="form-control"
               :list="field.meta.suggestions ? `${field.attribute}-list` : null"
               :aria-describedby="field.meta.help ? `${field.attribute}-help` : null"
               :disabled="field.readonly"/>
        <datalist v-if="field.meta.suggestions"
                  :id="`${field.attribute}-list`">
            <option v-for="(suggestion, index) in field.meta.suggestions"
                    :key="index"
                    :value="suggestion"/>
        </datalist>
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
                type: this.field.type || 'text',
                placeholder: this.field.placeholder || this.field.name,
                ...this.field.meta.attributes || {}
            };
        }
    }
};
</script>
