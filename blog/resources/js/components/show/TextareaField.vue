<template>
    <div>
        {{ field.value || '-' }}
    </div>
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
