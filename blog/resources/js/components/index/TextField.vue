<template>
    <span v-if="field.asHtml"
          v-html="field.value || '-'"/>
    <span v-else>
        {{ field.value || '-' }}
    </span>
</template>

<script>
export default {
    props: {
        field: {
            type: Object,
            required: true
        }
    }
};
</script>
