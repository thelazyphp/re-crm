<template>
    <div class="container-fluid">
        <h1 class="h3 mb-4 text-gray-800">
            Создать категорию
        </h1>
        <div class="row">
            <div class="col">
                <div class="card mb-4">
                    <div class="card-body">
                        <form
                            novalidate
                            @submit.prevent="submitForm"
                        >
                            <fieldset :disabled="form.loading">
                                <div class="form-group">
                                    <label for="name">
                                        Название
                                    </label>

                                    <input
                                        id="name"
                                        v-model="form.data.name"
                                        type="text"
                                        class="form-control"
                                        :class="{ 'is-invalid': form.errors.name.length > 0 }"
                                    />

                                    <div
                                        v-if="form.errors.name.length > 0"
                                        class="invalid-feedback"
                                    >
                                        {{ form.errors.name[0] }}
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="custom-control custom-checkbox">
                                        <input
                                            id="published"
                                            v-model="form.data.published"
                                            type="checkbox"
                                            class="custom-control-input"
                                            :class="{ 'is-invalid': form.errors.published.length > 0 }"
                                        />

                                        <label
                                            for="published"
                                            class="custom-control-label"
                                        >
                                            Опубликовать
                                        </label>

                                        <div
                                            v-if="form.errors.published.length > 0"
                                            class="invalid-feedback"
                                        >
                                            {{ form.errors.published[0] }}
                                        </div>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <button
                                        type="submit"
                                        class="btn btn-primary"
                                    >
                                        Создать
                                    </button>
                                </div>
                            </fieldset>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    name: 'CategoriesCreatePage',

    data () {
        return {
            form: {
                loading: false,
                data: {
                    name: '',
                    published: false
                },
                errors: {
                    name: [],
                    published: false
                }
            }
        }
    },

    mounted () {
        document.getElementById('name').focus();
    },

    methods: {
        clearFormErrors () {
            this.form.errors.name = [];
            this.form.errors.published = [];
        },

        async submitForm () {
            this.clearFormErrors();
            this.form.loading = true;

            try {
                await axios.post('/api/categories', this.form.data);
                this.$router.push('/categories');
            } catch (error) {
                console.log(error);
                if (typeof error.response !== 'undefined') {
                    if (error.response.status === 422) {
                        Object.assign(this.form.errors, error.response.data.errors);
                    }
                }
            } finally {
                this.form.loading = false;
            }
        }
    }
};
</script>
