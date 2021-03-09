<template>
    <div class="form-check mb-3">
        <input :id="field.attribute"
               v-model="value"
               v-bind="attributes"
               class="form-check-input"
               type="checkbox"
               :aria-describedby="field.meta.help ? `${field.attribute}-help` : null"
               :disabled="field.readonly"/>
        <label class="form-check-label"
               :for="field.attribute">
            {{ field.name }}
            <span v-if="field.required"
                  class="text-danger">*</span>
        </label>
        <field-errors v-if="hasErrors">
            {{ firstError }}
        </field-errors>
        <field-help v-if="field.meta.help"
                    :id="`${field.attribute}-help`">
            {{ field.meta.help }}
        </field-help>
    </div>
</template>

<script>
import FieldHelp from './FieldHelp.vue';
import FieldErrors from './FieldErrors.vue';
import formField from '../../mixins/formField';
import formFieldErrors from '../../mixins/formFieldErrors';

export default {
    components: {
        FieldHelp,
        FieldErrors,
    },

    mixins: [formField, formFieldErrors],

    data () {
        return {
            value: false
        };
    },

    computed: {
        attributes () {
            return {
                class: this.errorClass,
                ...this.field.meta.attributes || {}
            };
        }
    },

    methods: {
        initValue () {
            this.value = this.field.value === null || this.field.value === undefined
                ? false
                : this.field.value;
        }
    }
};
</script>
