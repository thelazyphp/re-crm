<template>
    <li class="list-group-item">
        <p>{{ file.name }}</p>

        <template v-if="! status">
            <div class="d-flex w-100 justify-content-between align-items-center">
                <small class="text-muted">{{ normalizedSize }}</small>

                <div>
                    <button
                        type="button"
                        class="btn btn-primary"
                        @click="$emit('upload')"
                    >
                        {{ $t('buttons.upload') }}
                    </button>
                    <button
                        type="button"
                        class="btn btn-secondary"
                        @click="$emit('remove')"
                    >
                        {{ $t('buttons.remove') }}
                    </button>
                </div>
            </div>
        </template>

        <template v-if="showProgress">
            <p v-if="status == 'uploading'">{{ $t('components.update_upload.uploading') }} ({{ progress }}%)</p>
            <p v-if="status == 'updating'">{{ $t('components.update_upload.updating') }} ({{ progress }}%)</p>

            <div class="progress">
                <div
                    class="progress-bar"
                    role="progressbar"
                    aria-valuemin="0"
                    aria-valuemax="100"
                    :style="'width: ' + progress + '%'"
                    :aria-valuenow="progress"
                >
                </div>
            </div>
        </template>
    </li>
</template>

<script>
    export default {
        props: {
            file: File,
            status: String,
            progress: Number
        },

        computed: {
            showProgress () {
                return ['uploading', 'updating'].includes(this.status)
            },

            normalizedSize () {
                const i = Math.floor(
                    Math.log(this.file.size) / Math.log(1024)
                )

                return (this.file.size / Math.pow(1024, i)).toFixed(2) * 1 + ' ' + ['B', 'kB', 'MB', 'GB', 'TB'][i]
            }
        }
    }
</script>
