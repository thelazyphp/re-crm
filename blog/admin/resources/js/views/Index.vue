<template>
    <div class="container">
        <h4 class="mb-4">
            {{ resourceInfo.pluralLabel }}
        </h4>
        <div class="d-flex flex-column flex-sm-row justify-content-between mb-4">
            <div class="row row-cols-auto">
                <div class="col-12">
                    <input v-model="search"
                           class="form-control"
                           type="search"
                           placeholder="Search"/>
                </div>
            </div>
            <router-link class="btn btn-primary mt-3 mt-sm-0"
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
        <template v-else-if="!resources.length">
            <p class="text-center">
                No {{ resourceInfo.pluralLabel }} Found.
            </p>
        </template>
        <template v-else>
            <resources-table :sort="sort"
                             :order="order"
                             :resources="resources"
                             :resource-name="resourceName"
                             :resource-info="resourceInfo"
                             @sort-resources="handleSortResources"
                             @delete-resource="handleDeleteResource"/>
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
            search: '',
            sort: null,
            order: null,
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
            this.selectedResources = [];
            this.loading = true;

            const params = {
                sort: this.sort,
                order: this.order
            };

            try {
                const {
                    data: {
                        resources
                    }
                } = await axios.get(`/resources/${this.resourceName}`, {
                    params
                });

                this.resources = resources;
            } catch (error) {
                //

                console.log(error);
            } finally {
                this.loading = false;
            }
        },

        async handleSortResources (event) {
            this.sort = event.sort;
            this.order = event.order;
            await this.fetchResources();
        },

        async handleDeleteResource (resource) {
            if (confirm(`Delete ${this.resourceInfo.label}?`)) {
                try {
                    await axios.delete(`/resources/${this.resourceName}/${resource.id.value}`);
                    const index = this.resources.findIndex(item => item.id.value === resource.id.value);
                    this.resources.splice(index, 1);
                    this.selectedResources.splice(index, 1);
                } catch (error) {
                    //

                    console.log(error);
                }
            }
        }
    }
};
</script>
