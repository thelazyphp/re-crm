<template>
    <div class="container">
        <h4 class="mb-4">
            {{ resourceInfo.pluralLabel }}
        </h4>
        <div class="text-end mb-4">
            <router-link class="btn btn-primary"
                         :to="{
                             name: 'create',
                             params: {
                                 resourceName
                             }
                         }"
                         role="button">
                Create {{ resourceInfo.label }}
            </router-link>
        </div>
    </div>
</template>

<script>
export default {
    computed: {
        resourceName () {
            return this.$route.params.resourceName;
        },

        resourceInfo () {
            return window.config.resources.find(item => item.name === this.resourceName);
        }
    }
};
</script>
