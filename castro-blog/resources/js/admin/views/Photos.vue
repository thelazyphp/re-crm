<template>
    <div class="container-fluid">
        <h1 class="h3 mb-4 text-gray-800">
            Фото
        </h1>
        <div class="row">
            <div class="col">
                <div class="form-group">
                    <div class="custom-file">
                        <input
                            id="file"
                            type="file"
                            class="custom-file-input"
                            @change="upload"
                        />

                        <label
                            for="file"
                            class="custom-file-label"
                            data-browse="Загрузить"
                        >
                            Выберите фото для загрузки
                        </label>
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
                console.log(error);
            }
        },

        async upload (event) {
            if (event.target.files.length) {
                const data = new FormData();
                data.append('image', event.target.files[0]);

                try {
                    await axios.post('/api/images', data);
                    await this.fetchImages();
                } catch (error) {
                    console.log(error);
                } finally {
                    event.target.value = null;
                }
            }
        }
    }
};
</script>
