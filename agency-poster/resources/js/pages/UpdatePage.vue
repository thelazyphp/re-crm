<template>
    <div class="card shadow">
        <div class="card-header">{{ $t('pages.update.update') }}</div>

        <div class="card-body">
            <p class="text-center text-primary">
                <i class="fas fa-cloud-upload-alt fa-5x"></i>
            </p>

            <div class="custom-file">
                <input
                    id="customFileLangHTML"
                    ref="files"
                    type="file"
                    class="custom-file-input"
                    multiple
                    accept="text/xml"
                    @change="addUpload"
                >
                <label
                    for="customFileLangHTML"
                    class="custom-file-label"
                    :data-browse="$t('buttons.browse')"
                >
                    {{ $t('pages.update.choose_files') }}
                </label>
            </div>
        </div>

        <ul class="list-group list-group-flush">
            <UpdateUploadItem
                v-for="(upload, index) in uploads"
                :key="index"
                :file="upload.file"
                :status="upload.status"
                :progress="upload.progress"
                @upload="$store.dispatch('update/uploadFile', index)"
                @remove="$store.commit('update/REMOVE_UPLOAD', index)"
            />
        </ul>

        <div v-if="uploads.length" class="card-body text-center">
            <button
                type="button"
                class="btn btn-primary"
                @click="$store.dispatch('update/uploadAllFiles')"
            >
                {{ $t('buttons.upload_all') }}
            </button>
            <button
                type="button"
                class="btn btn-secondary"
                @click="$store.commit('update/REMOVE_ALL_UPLOADS')"
            >
                {{ $t('buttons.remove_all') }}
            </button>
        </div>
    </div>
</template>

<script>
    import UpdateUploadItem from '../components/UpdateUploadItem'

    export default {
        components: { UpdateUploadItem },

        computed: {
            uploads () {
                return this.$store.state.update.uploads
            }
        },

        methods: {
            addUpload (event) {
                for (let file of event.target.files) {
                    this.$store.commit('update/ADD_UPLOAD', file)
                }

                this.$refs.files.value = null
            }
        }
    }
</script>
