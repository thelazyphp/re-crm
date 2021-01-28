<template>
    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-5">
        <div
            v-for="image in images"
            :key="image.id"
            class="col mb-4"
        >
            <div
                class="card"
                style="cursor: pointer;"
                @click="$emit('choose', image)"
            >
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
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    name: 'PhotosDialog',

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
        }
    }
};
</script>
