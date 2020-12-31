<template>
    <li class="list-group-item">
        <p>{{ feed.id }}</p>

        <template v-if="! feed.status">
            <div class="d-flex w-100 justify-content-between align-items-center">
                <small
                    v-if="feed.generated_at"
                    class="text-muted"
                >
                    {{ $t('components.feed.generated_at') }} {{ new Date(feed.generated_at).toLocaleString() }}
                </small>
                <small
                    v-else
                    class="text-muted"
                >
                    {{ $t('components.feed.not_generated_yet') }}
                </small>

                <div>
                    <button
                        type="button"
                        class="btn btn-primary"
                        @click="$emit('generate')"
                    >
                        {{ $t('buttons.generate') }}
                    </button>

                    <a
                        v-if="feed.url"
                        class="btn btn-secondary"
                        role="button"
                        target="_blank"
                        :href="feed.url"
                        :title="$t('components.feed.open_in_new_tab')"
                    >
                        <i class="fas fa-external-link-square-alt"></i>
                    </a>
                </div>
            </div>
        </template>

        <template v-if="showProgress">
            <p v-if="feed.status == 'generating'">{{ $t('components.feed.generating') }} ({{ feed.progress }}%)</p>

            <div class="progress">
                <div
                    class="progress-bar"
                    role="progressbar"
                    aria-valuemin="0"
                    aria-valuemax="100"
                    :style="'width: ' + feed.progress + '%'"
                    :aria-valuenow="feed.progress"
                >
                </div>
            </div>
        </template>
    </li>
</template>

<script>
    export default {
        props: {
            feed: {
                required: true
            }
        },

        computed: {
            showProgress () {
                return ['generating'].includes(this.feed.status)
            }
        }
    }
</script>
