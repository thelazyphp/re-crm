<template>
    <div class="mb-3">
        <label class="form-label"
               :for="fieldId">
            {{ field.name }}
            <span v-if="field.required"
                  class="text-danger">
                *
            </span>
        </label>
        <slot/>
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

    computed: {
        fieldId () {
            return `${this.field.attribute}-field`;
        }
    }
};
</script>
