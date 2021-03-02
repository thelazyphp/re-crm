<template>
    <base-field :field="field"
                :errors="errors">
        <input :id="field.attribute"
               v-model="value"
               v-bind="attributes"
               class="form-control"
               :disabled="field.readonly"
               :list="field.suggestions.length ? `${field.attribute}-list` : null"
               :aria-describedby="field.help ? `${field.attribute}-help` : null"/>
        <datalist v-if="field.suggestions.length"
                  :id="`${field.attribute}-list`">
            <option v-for="(suggestion, index) in field.suggestions"
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
    mixins: [formField, formFieldErrors],

    components: {
        BaseField,
    },

    computed: {
        attributes () {
            return {
                class: this.errorClass,
                type: this.field.meta.type || 'text',
                placeholder: this.field.meta.placeholder || this.field.name
            };
        }
    }
};
</script>
