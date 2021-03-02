<template>
    <div class="container">
        <h4 class="mb-4">
            {{ resourceInfo.label }} Details
        </h4>
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
                                     resourceId: resource.id.value,
                                     resourceName
                                 }
                             }"
                             role="button"
                             :title="`Edit ${resourceInfo.label}`">
                    <i class="far fa-edit"></i>
                </router-link>
                <button class="btn btn-primary ms-2"
                        type="button"
                        :title="`Delete ${resourceInfo.label}`"
                        @click="handleDeleteResource()">
                    <i class="far fa-trash-alt"></i>
                </button>
            </div>
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <dl class="row">
                        <template v-for="(field, index) in resource.fields">
                            <dt class="col-sm-2"
                                :key="`${field.attribute}-name-${index}`">
                                {{ field.name }}
                            </dt>
                            <dd class="col-sm-10"
                                :key="`${field.attribute}-value-${index}`">
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

export default {
    data () {
        return {
            loading: false,
            resource: []
        };
    },

    computed: {
        resourceName () {
            return this.$route.params.resourceName;
        },

        resourceId () {
            return this.$route.params.resourceId;
        },

        resourceInfo () {
            return window.config.resources.find(resource => resource.name === this.resourceName);
        }
    },

    async created () {
        await this.fetchResource();
    },

    methods: {
        async fetchResource () {
            this.loading = true;

            try {
                const {
                    data: {
                        resource
                    }
                } = await axios.get(`/resources/${this.resourceName}/${this.resourceId}`);

                this.resource = resource;
            } catch (error) {
                console.log(error);
            } finally {
                this.loading = false;
            }
        },

        async handleDeleteResource () {
            if (confirm(`Delete ${this.resourceInfo.label}?`)) {
                try {
                    await axios.delete(`/resources/${this.resourceName}/${this.resourceId}`);

                    this.$router.push({
                        name: 'index',
                        params: {
                            resourceName: this.resourceName
                        }
                    });
                } catch (error) {
                    //

                    console.log(error);
                }
            }
        }
    }
};
</script>
