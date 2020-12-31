<template>
    <div
        class="alert alert-dismissible fade show"
        :class="'alert-' + type"
        role="alert"
    >
        {{ message }}

        <button
            type="button"
            class="close"
            data-dismiss="alert"
            :aria-label="$t('components.alert.close')"
            @click="$emit('close')"
        >
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
</template>

<script>
    export default {
        props: {
            type: String,
            message: String
        }
    }
</script>

<style scoped>
    .close {
        outline: none !important;
    }
</style>
