<template>
    <div class="container-fluid">
        <h1 class="h3 mb-4 text-gray-800">
            Фото
        </h1>
        <div class="row">
            <div class="col">
                <div class="form-group mb-4">
                    <div class="custom-file">
                        <input
                            id="file"
                            type="file"
                            class="custom-file-input"
                            @change="uploadImage"
                        />

                        <label
                            for="file"
                            class="custom-file-label"
                            data-browse="Открыть"
                        >
                        </label>
                    </div>
                </div>
            </div>
        </div>
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-5">
            <div
                v-for="image in images"
                :key="image.id"
                class="col mb-4"
            >
                <div class="card">
                    <div
                        class="card-img-top"
                        :style="{
                            width: '100%',
                            height: '200px',
                            backgroundImage: `url(${image.url})`,
                            backgroundSize: 'cover',
                            backgroundRepeat: 'no-repeat',
                            backgroundPosition: 'center center'
                        }"
                    >
                    </div>

                    <div class="card-body">
                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input
                                    :id="`publishedCheck${image.id}`"
                                    type="checkbox"
                                    class="custom-control-input"
                                    :checked="image.published"
                                    @change="updateImageById(image.id, { published: $event.target.checked })"
                                />

                                <label
                                    :for="`publishedCheck${image.id}`"
                                    class="custom-control-label"
                                >
                                    Опубликовать
                                </label>
                            </div>
                        </div>
                        <div class="d-flex align-items-center">
                            <input
                                type="text"
                                class="form-control form-control-sm"
                                :value="image.name"
                                placeholder="Введите имя"
                                @blur="updateImageById(image.id, { name: $event.target.value })"
                            />

                            <a
                                href="javascript:void(0);"
                                class="ml-2"
                                title="Удалить"
                                @click.prevent="deleteImageById(image.id)"
                            >
                                <i class="far fa-trash-alt"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    name: 'PhotosPage',

    data () {
        return {
            images: []
        }
    },

    async created () {
        await this.fetchImages();
    },

    methods: {
        async fetchImages () {
            try {
                const { data } = await axios.get('/api/images');
                this.images = data.data;
            } catch (error) {
                //

                console.log(error);
            }
        },

        async updateImageById (id, data) {
            try {
                await axios.put(`/api/images/${id}`, data);
            } catch (error) {
                //

                console.log(error);
            }
        },

        async deleteImagesByIds (ids) {
            for (const id of ids) {
                try {
                    await axios.delete(`/api/images/${id}`);
                } catch (error) {
                    //

                    console.log(error);
                }
            }

            await this.fetchImages();
        },

        async deleteImageById (id) {
            if (confirm('Удалить фото?')) {
                await this.deleteImagesByIds([id]);
            }
        },

        async uploadImage (event) {
            if (event.target.files.length) {
                const data = new FormData();
                data.append('image', event.target.files[0]);

                try {
                    await axios.post('/api/images', data);
                    await this.fetchImages();
                } catch (error) {
                    //

                    console.log(error);
                } finally {
                    event.target.value = null;
                }
            }
        }
    }
};
</script>
