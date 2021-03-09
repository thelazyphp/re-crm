<template>
    <div class="container">
        <h4 class="mb-4">
            {{ resourceInfo.pluralLabel }}
        </h4>
        <template v-if="initialLoading">
            <v-loader/>
        </template>
        <template v-else>
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
                                     resourceKey
                                 }
                             }"
                             role="button">
                    Create {{ resourceInfo.name }}
                </router-link>
            </div>
            <template v-if="loading">
                <v-loader/>
            </template>
            <template v-else-if="!resources.length">
                <p class="text-center">
                    No {{ resourceInfo.pluralName }} Found.
                </p>
            </template>
            <template v-else>
                <resources-table :sort="sort"
                                 :order="order"
                                 :resources="resources"
                                 :resource-key="resourceKey"
                                 :resource-info="resourceInfo"
                                 @sort-resources="handleSortResources"
                                 @delete-resource="handleDeleteResource"/>
            </template>
        </template>
    </div>
</template>

<script>
import axios from 'axios';
import VLoader from '../components/VLoader.vue';
import ResourcesTable from '../components/ResourcesTable.vue';

export default {
    components: {
        VLoader,
        ResourcesTable,
    },

    data () {
        return {
            initialLoading: true,
            loading: false,
            search: '',
            sort: null,
            order: null,
            filters: [],
            resources: []
        };
    },

    computed: {
        resourceKey () {
            return this.$route.params.resourceKey;
        },

        resourceInfo () {
            return window.config.resources.find(resource => resource.key === this.resourceKey);
        }
    },

    watch: {
        async '$route' () {
            await this.init();
        }
    },

    async created () {
        await this.init();
    },

    methods: {
        async init () {
            this.initialLoading = true;

            //

            await this.fetchResources();

            //

            this.initialLoading = false;
        },

        async fetchResources () {
            this.loading = true;

            try {
                const {
                    data: {
                        resources
                    }
                } = await axios.get(`/api/resources/${this.resourceKey}`, {
                    params: {
                        q: this.search,
                        sort: this.sort,
                        order: this.order
                    }
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
            if (confirm(`Delete ${this.resourceInfo.name}?`)) {
                this.loading = true;

                try {
                    await axios.delete(`/api/resources/${this.resourceKey}/${resource.id.value}`);
                    this.resources.splice(this.resources.findIndex(item => item.id.value === resource.id.value), 1);
                } catch (error) {
                    //

                    console.log(error);
                } finally {
                    this.loading = false;
                }
            }
        }
    }
};
</script>
