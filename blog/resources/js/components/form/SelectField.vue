<template>
    <base-field :field="field"
                :errors="errors">
        <template v-if="field.searchable">

            <!-- Fake invalid input to display select's invalid feedback -->
            <input class="is-invalid"
                   type="hidden"/>

            <v-select v-model="value"
                      :options="field.meta.options"
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
                    :aria-describedby="field.meta.help ? `${field.attribute}-help` : null"
                    :disabled="field.readonly">
                <option value=""
                        :disabled="!field.nullable">
                    {{ placeholder }}
                </option>
                <option v-for="(option, index) in field.meta.options"
                        :key="index"
                        :value="option.value">
                    {{ option.label }}
                </option>
            </select>
        </template>
    </base-field>
</template>

<script>
import VSelect from '../VSelect.vue';
import BaseField from './BaseField.vue';
import formField from '../../mixins/formField';
import formFieldErrors from '../../mixins/formFieldErrors';

export default {
    components: {
        VSelect,
        BaseField,
    },

    mixins: [formField, formFieldErrors],

    computed: {
        attributes () {
            return {
                class: this.errorClass,
                ...this.field.meta.attributes || {}
            };
        },

        placeholder () {
            return this.field.placeholder || 'Choose an option...';
        }
    }
};
</script>
