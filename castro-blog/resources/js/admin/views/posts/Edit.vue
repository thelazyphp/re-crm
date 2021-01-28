<template>
    <div class="container-fluid">
        <h1 class="h3 mb-4 text-gray-800">
            Редактировать пост
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
                                <div class="d-flex flex-column flex-md-row mb-4">
                                    <button
                                        type="button"
                                        class="btn btn-primary"
                                        data-toggle="modal"
                                        data-target="#photosDialogModal"
                                    >
                                        Выбрать фото
                                    </button>

                                    <button
                                        v-if="chosenPhoto"
                                        type="button"
                                        class="btn btn-primary mt-2 mt-md-0 ml-0 ml-md-2"
                                        @click="chosenPhoto = null"
                                    >
                                        Удалить фото
                                    </button>
                                </div>

                                <div
                                    v-if="chosenPhoto"
                                    class="rounded shadow-sm mb-4"
                                    :style="{
                                        width: '200px',
                                        height: '200px',
                                        backgroundImage: `url(${chosenPhoto.url})`,
                                        backgroundSize: 'cover',
                                        backgroundRepeat: 'no-repeat',
                                        backgroundPosition: 'center center'
                                    }"
                                >
                                </div>

                                <div class="form-group">
                                    <label for="category">
                                        Категория
                                    </label>

                                    <select
                                        id="category"
                                        v-model="form.data.category_id"
                                        class="custom-select"
                                        :class="{ 'is-invalid': form.errors.category_id.length > 0 }"
                                    >
                                        <option :value="null">
                                            Без категории
                                        </option>

                                        <option
                                            v-for="category in categories"
                                            :key="category.id"
                                            :value="category.id"
                                        >
                                            {{ category.name }}
                                        </option>
                                    </select>

                                    <div
                                        v-if="form.errors.category_id.length > 0"
                                        class="invalid-feedback"
                                    >
                                        {{ form.errors.category_id[0] }}
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="title">
                                        Заголовок
                                    </label>

                                    <input
                                        id="title"
                                        v-model="form.data.title"
                                        type="text"
                                        class="form-control"
                                        :class="{ 'is-invalid': form.errors.title.length > 0 }"
                                    />

                                    <div
                                        v-if="form.errors.title.length > 0"
                                        class="invalid-feedback"
                                    >
                                        {{ form.errors.title[0] }}
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="body">
                                        Текст
                                    </label>

                                    <textarea
                                        id="body"
                                        v-model="form.data.body"
                                        type="text"
                                        class="form-control"
                                        :class="{ 'is-invalid': form.errors.body.length > 0 }"
                                    >
                                    </textarea>

                                    <div
                                        v-if="form.errors.body.length > 0"
                                        class="invalid-feedback"
                                    >
                                        {{ form.errors.body[0] }}
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
                                        Сохранить
                                    </button>
                                </div>
                            </fieldset>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div
            id="photosDialogModal"
            class="modal fade"
            tabindex="-1"
            aria-labelledby="photosDialogModalLabel"
            aria-hidden="true"
        >
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5
                            id="photosDialogModalLabel"
                            class="modal-title"
                        >
                            Выбрать фото
                        </h5>

                        <button
                            type="button"
                            class="close"
                            data-dismiss="modal"
                            aria-label="Закрыть"
                        >
                            <span aria-hidden="true">
                                &times;
                            </span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <PhotosDialog @choose="chosenPhoto = $event, closeModal()"/>
                    </div>
                    <div class="modal-footer">
                        <button
                            type="button"
                            class="btn btn-secondary"
                            data-dismiss="modal"
                        >
                            Закрыть
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
import PhotosDialog from '../../components/PhotosDialog.vue';

export default {
    name: 'PostsEditPage',

    components: {
        PhotosDialog
    },

    data () {
        return {
            chosenPhoto: null,
            categories: [],
            form: {
                loading: false,
                data: {
                    category_id: null,
                    image_id: null,
                    title: '',
                    body: '',
                    published: false
                },
                errors: {
                    category_id: [],
                    image_id: [],
                    title: [],
                    body: [],
                    published: []
                }
            }
        }
    },

    computed: {
        id () {
            return this.$route.params.id;
        }
    },

    watch: {
        chosenPhoto (value) {
            this.form.data.image_id = value === null
                ? null
                : value.id;
        }
    },

    async created () {
        await this.fetchCategories();
    },

    async mounted () {
        window.tinymce.init({selector:'#body'});
        document.getElementById('title').focus();
        await this.fetchPost();
    },

    methods: {
        async fetchCategories () {
            try {
                const { data } = await axios.get('/api/categories');
                this.categories = data.data;
            } catch (error) {
                //

                console.log(error);
            }
        },

        async fetchPost () {
            try {
                const { data } = await axios.get(`/api/posts/${this.id}`);

                if (data.data.category) {
                    this.form.data.category_id = data.data.category.id;
                }

                if (data.data.image) {
                    this.chosenPhoto = data.data.image;
                }

                this.form.data.title = data.data.title;
                window.tinymce.activeEditor.setContent(data.data.body);
                this.form.data.published = data.data.published;
            } catch (error) {
                //

                console.log(error);
            }
        },

        closeModal () {
            window.$('#photosDialogModal').modal('hide');
        },

        clearFormErrors () {
            for (const key in this.form.errors) {
                this.form.errors[key] = [];
            }
        },

        async submitForm () {
            this.clearFormErrors();
            this.form.loading = true;

            this.form.data.body = window.tinymce.activeEditor.getContent();

            try {
                await axios.put(`/api/posts/${this.id}`, this.form.data);
                this.$router.push('/posts');
            } catch (error) {
                console.log(error);
                if (typeof error.response !== 'undefined') {
                    if (error.response.status === 422) {
                        Object.assign(
                            this.form.errors,
                            error.response.data.errors
                        );
                    }
                }
            } finally {
                this.form.loading = false;
            }
        }
    }
};
</script>
