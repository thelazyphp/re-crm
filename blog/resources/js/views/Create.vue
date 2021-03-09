<template>
    <div class="container">
        <h4 class="mb-4">
            Create {{ resourceInfo.name }}
        </h4>
        <template v-if="loading">
            <v-loader/>
        </template>
        <template v-else>
            <div class="card border-0 shadow-sm">
                <form class="card-body"
                      @submit.prevent="handleSubmit">
                    <fieldset :disabled="pending">
                        <component v-for="(field, index) in fields"
                                   :is="`form-${field.component}`"
                                   :key="index"
                                   :field="field"
                                   :errors="errors"/>
                        <div class="text-end">
                            <button class="btn btn-secondary"
                                    type="button"
                                    @click="$router.back()">
                                Back
                            </button>
                            <button class="btn btn-primary ms-2"
                                    type="submit">
                                Create {{ resourceInfo.name }}
                            </button>
                        </div>
                    </fieldset>
                </form>
            </div>
        </template>
    </div>
</template>

<script>
import axios from 'axios';
import Errors from '../utils/Errors';
import VLoader from '../components/VLoader.vue';

export default {
    components: {
        VLoader,
    },

    data () {
        return {
            loading: true,
            pending: false,
            fields: [],
            errors: new Errors()
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

    async created () {
        await this.fetchFields();
        this.loading = false;
    },

    methods: {
        async fetchFields () {
            try {
                const {
                    data: {
                        fields
                    }
                } = await axios.get(`/api/resources/${this.resourceKey}/create-fields`);

                this.fields = fields;
            } catch (error) {
                //

                console.log(error);
            }
        },

        async handleSubmit() {
            this.errors.clear();
            this.pending = true;

            try {
                const data = new FormData();

                this.fields.forEach(field => {
                    field.fill(data);
                });

                const {
                    data: {
                        redirectTo
                    }
                } = await axios.post(`/api/resources/${this.resourceKey}`, data);

                this.$router.push(redirectTo);
            } catch (error) {
                console.log(error);

                if (error.response !== undefined) {
                    if (error.response.status === 422) {
                        this.errors = new Errors(
                            error.response.data.errors
                        );
                    }
                }
            } finally {
                this.pending = false;
            }
        }
    }
};
</script>
