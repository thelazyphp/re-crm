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
                <button class="btn btn-primary mx-1"
                        type="button"
                        @click="$router.back()">
                    Back
                </button>
                <router-link class="btn btn-primary mx-1"
                             :to="{
                                 name: 'update',
                                 params: {
                                     resourceId: resource.id.value,
                                     resourceName
                                 }
                             }"
                             :title="`Edit ${resourceInfo.label}`">
                    <i class="far fa-edit"></i>
                </router-link>
                <a class="btn btn-primary mx-1"
                   href=""
                   :title="`Delete ${resourceInfo.label}`">
                    <i class="far fa-trash-alt"></i>
                </a>
            </div>
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li v-for="(field, index) in resource.fields"
                            :key="index"
                            class="list-group-item">
                            <div class="row">
                                <div class="col-sm-3">
                                    <strong>
                                        {{ field.name }}
                                    </strong>
                                </div>
                                <div class="col-sm-9">
                                    <component :is="`detail-${field.component}`"
                                               :field="field"/>
                                </div>
                            </div>
                        </li>
                    </ul>
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
        resourceId () {
            return this.$route.params.resourceId;
        },

        resourceName () {
            return this.$route.params.resourceName;
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
        }
    }
};
</script>
