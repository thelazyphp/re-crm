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
        <template v-if="loading">
            <div class="text-center">
                <div class="spinner-grow text-primary"
                     role="status">
                    <span class="visually-hidden">
                        Loading...
                    </span>
                </div>
            </div>
        </template>
        <template v-else-if="resources.length">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <resources-table :resources="resources"
                                     :resource-name="resourceName"
                                     :resource-info="resourceInfo"
                                     @delete-resource="handleDeleteResource"/>
                </div>
            </div>
        </template>
        <template v-else>
            <p class="text-center">
                No {{ resourceInfo.pluralLabel }} Found.
            </p>
        </template>
    </div>
</template>

<script>
import axios from 'axios';
import ResourcesTable from '../components/ResourcesTable.vue';

export default {
    components: {
        ResourcesTable,
    },

    data () {
        return {
            loading: false,
            resources: [],
            selectedResources: []
        };
    },

    computed: {
        resourceName () {
            return this.$route.params.resourceName;
        },

        resourceInfo () {
            return window.config.resources.find(resource => resource.name === this.resourceName);
        }
    },

    watch: {
        async '$route' () {
            await this.fetchResources();
        }
    },

    async created () {
        await this.fetchResources();
    },

    methods: {
        async fetchResources () {
            this.loading = true;

            try {
                const {
                    data: {
                        resources
                    }
                } = await axios.get(`/resources/${this.resourceName}`);

                this.resources = resources;
            } catch (error) {
                //

                console.log(error);
            } finally {
                this.loading = false;
            }
        },

        async handleDeleteResource (resource) {
            if (confirm(`Delete ${resourceInfo.label}?`)) {
                await axios.delete(`/resources/${this.resourceName}/${resource.id.value}`);
                this.resources.splice(this.resources.findIndex(item => item.id.value === resource.id.value), 1);
            }
        }
    }
};
</script>
