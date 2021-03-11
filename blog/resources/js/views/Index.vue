<template>
    <div class="container">
            <h4 class="mb-4">
                {{ resourceInfo.pluralName }}
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
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white d-flex align-items-center justify-content-between">
                            <input class="form-check-input"
                                   type="checkbox"
                                   :title="`Check All ${resourceInfo.pluralName}`"
                                   :aria-label="`Check All ${resourceInfo.pluralName}`"
                                   @change="handleCheckAllResources"/>
                            <div class="d-flex align-items-center">
                                <div v-if="checkedResources.length && actions.length"
                                     class="row row-cols-auto">
                                    <div class="col-12">
                                        <action-select :actions="actions"/>
                                    </div>
                                </div>
                                <a v-if="checkedResources.length"
                                   class="ms-2"
                                   href=""
                                   role="button"
                                   :title="`Delete Checked ${resourceInfo.pluralName}`"
                                   @click.prevent="handleDeleteResources(checkedResourcesIds)">
                                    <i class="far fa-trash-alt"></i>
                                </a>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <resources-table
                                :sort="sort"
                                :order="order"
                                :resources="resources"
                                :checked-resources="checkedResources"
                                :resource-key="resourceKey"
                                :resource-info="resourceInfo"
                                @check-resource="handleCheckResource"
                                @sort-resources="handleSortResources"
                                @delete-resource="handleDeleteResources([$event.id.value])"/>
                        </div>
                        <!-- <div class="card-footer bg-light d-flex align-items-center justify-content-between">
                            <a class="text-decoration-none"
                               href="">
                                Previous
                            </a>
                            <span class="text-secondary mx-auto">
                                1-{{ resources.length }} of {{ resources.length }}
                            </span>
                            <a class="text-decoration-none"
                               href="">
                                Next
                            </a>
                        </div> -->
                    </div>
                </template>
            </template>
    </div>
</template>

<script>
import axios from 'axios';
import VLoader from '../components/VLoader.vue';
import ActionSelect from '../components/ActionSelect.vue';
import ResourcesTable from '../components/ResourcesTable.vue';

export default {
    components: {
        VLoader,
        ActionSelect,
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
            actions: [],
            resources: [],
            checkedResources: []
        };
    },

    computed: {
        resourceKey () {
            return this.$route.params.resourceKey;
        },

        resourceInfo () {
            return window.config.resources.find(resource => resource.key === this.resourceKey);
        },

        checkedResourcesIds() {
            return this.checkedResources.map(resource => resource.id.value);
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
            await this.fetchActions();
            await this.fetchResources();
            this.initialLoading = false;
        },

        async fetchActions () {

            try {
                const {
                    data: {
                        actions
                    }
                } = await axios.get(`/api/resources/${this.resourceKey}/actions`);

                this.actions = actions;
            } catch (error) {
                //

                console.log(error);
            }
        },

        async fetchResources () {
            this.checkedResources = [];
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

        handleCheckResource (event) {
            if (event.checked) {
                this.checkedResources.push(event.resource);
            } else {
                this.checkedResources.splice(
                    this.checkedResources.findIndex(resource => resource === event.resource), 1
                );
            }
        },

        handleCheckAllResources (event) {
            if (event.target.checked) {
                this.checkedResources = this.resources;
            } else {
                this.checkedResources = [];
            }
        },

        async handleDeleteResources (ids) {
            if (confirm(`Delete Selected ${this.resourceInfo.pluralName}?`)) {
                this.loading = true;

                try {
                    await axios.delete(`/api/resources/${this.resourceKey}`, {
                        params: {
                            resources: ids.join(',')
                        }
                    });

                    await this.fetchResources();
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
