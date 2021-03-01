<template>
    <p>
        {{ field.value }}
    </p>
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
