<template>
    <span>
        {{ field.value }}
    </span>
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
        value () {
            return this.field.value.length <= 15
                ? this.field.value
                : `${this.field.value.substr(0, 15)}...`;
        }
    }
};
</script>
