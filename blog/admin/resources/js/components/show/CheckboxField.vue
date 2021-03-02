<template>
    <span v-if="field.value === true"
          class="text-primary">
        <i class="far fa-check-circle"></i>
    </span>
    <span v-else
          class="text-danger">
        <i class="far fa-times-circle"></i>
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
