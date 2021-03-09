<template>
    <label class="form-label">
        <slot/>
        <span v-if="required"
              class="text-danger">*</span>
    </label>
</template>

<script>
export default {
    props: {
        required: {
            type: Boolean,
            default: false
        }
    }
};
</script>
