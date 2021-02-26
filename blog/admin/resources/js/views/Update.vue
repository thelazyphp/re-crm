<template>
    <div class="container">
        <h4 class="mb-4">
            Update {{ resourceInfo.label }}
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
            <div class="card border-0 shadow-sm mb-4">
                <form class="card-body"
                      @submit.prevent="handleSubmit">
                    <fieldset :disabled="submitting">
                        <component v-for="(field, index) in fields"
                                   :is="field.component"
                                   :key="index"
                                   :field="field"/>
                        <div class="text-end">
                            <button class="btn btn-primary"
                                    type="submit">
                                Update {{ resourceInfo.label }}
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

export default {
    data () {
        return {
            loading: false,
            fields: [],
            submitting: false
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
            return window.config.resources.find(item => item.name === this.resourceName);
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
                } = await axios.get(`/resources/${this.resourceName}/${this.resourceId}/update-fields`);

                this.fields = fields;
            } catch (error) {
                console.log(error);
            } finally {
                this.loading = false;
            }
        },

        async handleSubmit() {
            this.submitting = true;

            try {
                //
            } catch (error) {
                console.log(error);
            } finally {
                this.submitting = false;
            }
        }
    }
};
</script>
