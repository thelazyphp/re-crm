<template>
    <div class="form-check mb-3">
        <input :id="fieldId"
               v-model="value"
               v-bind="attributes"
               class="form-check-input"
               type="checkbox"
               :aria-describedby="!field.help ? null : `${fieldId}-help`"/>
        <label class="form-check-label"
               :for="fieldId">
            {{ field.name }}
            <span v-if="field.required"
                  class="text-danger">
                *
            </span>
        </label>
        <div v-if="field.help"
             :id="`${fieldId}-help`"
             class="form-text"
             v-html="field.help"/>
    </div>
</template>

<script>
export default {
    props: {
        field: {
            type: Object,
            required: true
        }
    },

    data () {
        return {
            value: false
        }
    },

    computed: {
        fieldId () {
            return `${this.field.attribute}-field`;
        },

        attributes () {
            return {
                disabled: this.field.readonly || false,
            };
        }
    },

    created () {
        this.value = this.field.value || this.field.default || false;
    },

    methods: {
        fill (data) {
            data.append(this.field.attribute, this.value);
        }
    }
};
</script>
