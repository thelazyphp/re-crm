<template>
    <base-field :field="field"
                :errors="errors">
        <template v-if="field.searchable">

            <!-- For invalid feedback handling -->
            <input class="is-invalid"
                   type="hidden"/>

            <app-select v-model="value"
                        :options="field.options"
                        :error-class="errorClass"
                        :readonly="field.readonly"
                        :nullable="field.nullable"
                        :placeholder="placeholder"/>
        </template>
        <template v-else>
            <select :id="field.attribute"
                    v-model="value"
                    v-bind="attributes"
                    class="form-select"
                    :disabled="field.readonly"
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
        </template>
    </base-field>
</template>

<script>
import BaseField from './BaseField.vue';
import AppSelect from '../AppSelect.vue';
import formField from '../../mixins/formField';
import formFieldErrors from '../../mixins/formFieldErrors';

export default {
    mixins: [formField, formFieldErrors],

    components: {
        BaseField,
        AppSelect,
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
