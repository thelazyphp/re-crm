<template>
    <div class="card shadow">
        <div class="card-header">{{ $t('pages.feeds.feeds') }}</div>

        <div class="card-body text-center text-primary">
            <i class="fas fa-file-code fa-5x"></i>
        </div>

        <ul class="list-group list-group-flush">
            <FeedItem
                v-for="(feed, index) in feeds"
                :key="index"
                :feed="feed"
                @generate="$store.dispatch('feeds/generate', index)"
            />
        </ul>

        <div class="card-body text-center">
            <button
                type="button"
                class="btn btn-primary"
                @click="$store.dispatch('feeds/generateAll')"
            >
                {{ $t('buttons.generate_all') }}
            </button>
        </div>
    </div>
</template>

<script>
    import FeedItem from '../components/FeedItem'
    import * as FeedsService from '../services/feeds.service'

    export default {
        components: { FeedItem },
        
        computed: {
            feeds () {
                return this.$store.state.feeds.items
            }
        },

        created () {
            if (! this.feeds.length) {
                FeedsService.load()
            }
        }
    }
</script>
