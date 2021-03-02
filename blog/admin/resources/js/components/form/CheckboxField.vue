<template>
    <div class="form-check mb-3">
        <input :id="field.attribute"
               v-model="value"
               v-bind="attributes"
               class="form-check-input"
               type="checkbox"
               :disabled="field.readonly"
               :aria-describedby="field.help ? `${field.attribute}-help` : null"/>
        <label class="form-check-label"
               :for="field.attribute">
            {{ field.name }}
            <span v-if="field.required"
                  class="text-danger">*</span>
        </label>
        <field-errors v-if="hasErrors">
            {{ firstError }}
        </field-errors>
        <field-help v-if="field.help"
                    :id="`${field.attribute}-help`">
            {{ field.help }}
        </field-help>
    </div>
</template>

<script>
import FieldHelp from './FieldHelp.vue';
import FieldErrors from './FieldErrors.vue';
import formField from '../../mixins/formField';
import formFieldErrors from '../../mixins/formFieldErrors';

export default {
    mixins: [formField, formFieldErrors],

    components: {
        FieldHelp,
        FieldErrors,
    },

    data () {
        return {
            value: false
        };
    },

    computed: {
        attributes () {
            return {
                class: this.errorClass
            };
        }
    }
};
</script>
