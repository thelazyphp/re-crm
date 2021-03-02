<template>
    <div class="container">
        <h4 class="mb-4">
            Create {{ resourceInfo.label }}
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
            <div class="card border-0 shadow-sm">
                <form class="card-body"
                      @submit.prevent="handleSubmit">
                    <fieldset :disabled="submitting">
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
                                Create {{ resourceInfo.label }}
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
import Errors from '../helpers/Errors';

export default {
    data () {
        return {
            loading: false,
            submitting: false,
            fields: [],
            errors: new Errors()
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

    async created () {
        await this.fetchFields();
    },

    methods: {
        async fetchFields () {
            this.loading = true;

            try {
                const {
                    data: {
                        fields
                    }
                } = await axios.get(`/resources/${this.resourceName}/create-fields`);

                this.fields = fields;
            } catch (error) {
                //

                console.log(error);
            } finally {
                this.loading = false;
            }
        },

        async handleSubmit() {
            this.submitting = true;
            this.errors.clear();

            try {
                const data = {};

                this.fields.forEach(field => {
                    field.fill(data);
                });

                const {
                    data: {
                        redirectTo
                    }
                } = await axios.post(`/resources/${this.resourceName}`, data);

                this.$router.push(redirectTo);
            } catch (error) {
                console.log(error);

                if (error.response.status === 422) {
                    this.errors = new Errors(
                        error.response.data.errors
                    );
                }
            } finally {
                this.submitting = false;
            }
        }
    }
};
</script>
