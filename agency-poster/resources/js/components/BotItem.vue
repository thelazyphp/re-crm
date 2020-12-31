<template>
    <li class="list-group-item">
        <div class="custom-control custom-switch">
            <input
                :id="bot.id + '-switch'"
                type="checkbox"
                class="custom-control-input"
                :checked="bot.active"
            >
            <label
                class="custom-control-label"
                :for="bot.id + '-switch'"
            >
                {{ bot.id }}
            </label>
        </div>

        <div class="d-flex w-100 justify-content-between align-items-center mt-3">
            <small
                v-if="bot.run_at"
                class="text-muted"
            >
                {{ $t('components.bot.run_at') }} {{ new Date(bot.run_at).toLocaleString() }}
            </small>
            <small
                v-else
                class="text-muted"
            >
                {{ $t('components.bot.not_run_yet') }}
            </small>

            <button
                type="button"
                class="btn btn-primary"
                :disabled="bot.running"
                :title="bot.running ? '' : $t('buttons.run')"
                @click="$emit('run')"
            >
                <i class="fas fa-play"></i>
            </button>
        </div>
    </li>
</template>

<script>
    export default {
        props: {
            bot: {
                required: true
            }
        }
    }
</script>
