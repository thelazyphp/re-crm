<template>
    <div class="card shadow">
        <div class="card-header">{{ $t('pages.bots.bots') }}</div>

        <div class="card-body text-center text-primary">
            <i class="fas fa-robot fa-5x"></i>
        </div>

        <ul class="list-group list-group-flush">
            <BotItem
                v-for="(bot, index) in bots"
                :key="index"
                :bot="bot"
            />
        </ul>
    </div>
</template>

<script>
    import BotItem from '../components/BotItem'
    import * as BotsService from '../services/bots.service'

    export default {
        components: { BotItem },

        computed: {
            bots () {
                return this.$store.state.bots.items
            }
        },

        created () {
            if (! this.bots.length) {
                BotsService.load()
            }
        }
    }
</script>
