<template>
    <field-wrapper>
        <field-label :for="field.attribute"
                     :required="field.required">
            {{ field.name }}
        </field-label>
        <slot/>
        <field-errors v-if="hasErrors">
            {{ firstError }}
        </field-errors>
        <field-help v-if="field.meta.help"
                    :id="`${field.attribute}-help`">
            {{ field.meta.help }}
        </field-help>
    </field-wrapper>
</template>

<script>
import FieldHelp from './FieldHelp.vue';
import FieldLabel from './FieldLabel.vue';
import FieldErrors from './FieldErrors.vue';
import FieldWrapper from './FieldWrapper.vue';
import formFieldErrors from '../../mixins/formFieldErrors';

export default {
    components: {
        FieldHelp,
        FieldLabel,
        FieldErrors,
        FieldWrapper,
    },

    mixins: [formFieldErrors],

    props: {
        field: {
            type: Object,
            required: true
        }
    }
};
</script>
