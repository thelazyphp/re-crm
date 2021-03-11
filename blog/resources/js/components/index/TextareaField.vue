<template>
    <span>
        {{ value || '-' }}
    </span>
</template>

<script>
const MAX_LENGTH = 15;

export default {
    props: {
        field: {
            type: Object,
            required: true
        }
    },

    computed: {
        value () {
            return this.field.value.length <= MAX_LENGTH
                ? this.field.value
                : `${this.field.value.substr(0, MAX_LENGTH)}...`;
        }
    }
};
</script>
