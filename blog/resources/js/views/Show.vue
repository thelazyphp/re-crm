<template>
    <div class="container">
        <h4 class="mb-4">
            {{ resourceInfo.name }} Details
        </h4>
        <template v-if="loading">
            <v-loader/>
        </template>
        <template v-else>
            <div class="text-end mb-4">
                <button class="btn btn-secondary"
                        type="button"
                        @click="$router.back()">
                    Back
                </button>
                <router-link class="btn btn-primary ms-2"
                             :to="{
                                 name: 'update',
                                 params: {
                                     resourceKey,
                                     resourceId: resource.id.value
                                 }
                             }"
                             role="button"
                             :title="`Edit ${resourceInfo.name}`">
                    <i class="far fa-edit"></i>
                </router-link>
                <button class="btn btn-primary ms-2"
                        type="button"
                        :title="`Delete ${resourceInfo.name}`"
                        @click="handleDeleteResource">
                    <i class="far fa-trash-alt"></i>
                </button>
            </div>
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <dl class="row">
                        <template v-for="(field, index) in resource.fields">
                            <dt class="col-sm-2"
                                :key="`field-name-${index}`">
                                {{ field.name }}
                            </dt>
                            <dd class="col-sm-10"
                                :key="`field-value-${index}`">
                                <component :is="`show-${field.component}`"
                                           :field="field"/>
                            </dd>
                        </template>
                    </dl>
                </div>
            </div>
        </template>
    </div>
</template>

<script>
import axios from 'axios';
import VLoader from '../components/VLoader.vue';

export default {
    components: {
        VLoader,
    },

    data () {
        return {
            loading: true,
            resource: null
        };
    },

    computed: {
        resourceKey () {
            return this.$route.params.resourceKey;
        },

        resourceId () {
            return this.$route.params.resourceId;
        },

        resourceInfo () {
            return window.config.resources.find(resource => resource.key === this.resourceKey);
        }
    },

    async created () {
        await this.fetchResource();
        this.loading = false;
    },

    methods: {
        async fetchResource () {
            try {
                const {
                    data: {
                        resource
                    }
                } = await axios.get(`/api/resources/${this.resourceKey}/${this.resourceId}`);

                this.resource = resource;
            } catch (error) {
                //

                console.log(error);
            }
        },

        async handleDeleteResource () {
            if (confirm(`Delete ${this.resourceInfo.name}?`)) {
                this.loading = true;

                try {
                    await axios.delete(`/api/resources/${this.resourceKey}/${this.resourceId}`);

                    this.$router.push({
                        name: 'index',
                        params: {
                            resourceKey: this.resourceKey
                        }
                    });
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
