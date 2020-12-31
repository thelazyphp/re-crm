<template>
    <div class="card shadow">
        <div class="card-header">{{ $t('pages.logs.logs') }}</div>

        <div class="card-body">
            <div class="text-center">
                <p class="text-primary">
                    <i class="fas fa-book fa-5x"></i>
                </p>
            </div>

            <ul class="nav nav-tabs mb-3">
                <li class="nav-item">
                    <a
                        href="javascript:void(0)"
                        class="nav-link"
                        :class="{ active: type == 'bots' }"
                        @click="type = 'bots'"
                    >
                        {{ $t('pages.logs.bots') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a
                        href="javascript:void(0)"
                        class="nav-link"
                        :class="{ active: type == 'jandex_geolocations' }"
                        @click="type = 'jandex_geolocations'"
                    >
                        {{ $t('pages.logs.jandex_geolocations') }}
                    </a>
                </li>
            </ul>

            <template v-if="type == 'bots'">
                <div
                    v-for="(logs, bot) in botLogs"
                    :key="bot"
                    :id="`bot-${bot}-logs`"
                >
                    <h1 class="display-4 text-center text-primary">{{ bot }}</h1>

                    <div
                        v-for="(log, filename, index) in logs"
                        :key="index"
                        :id="`bot-${bot}-log-${index}`"
                    >
                        <p>
                            <a
                                class="dropdown-toggle"
                                data-toggle="collapse"
                                aria-expanded="false"
                                :href="`#bot-${bot}-log-${index}-collapse`"
                                :aria-controls="`bot-${bot}-log-${index}-collapse`"
                            >
                                {{ filename }}
                            </a>
                        </p>

                        <p
                            :id="`bot-${bot}-log-${index}-collapse`"
                            class="collapse"
                        >
                            <pre>{{ log }}</pre>
                        </p>
                    </div>
                </div>
            </template>

            <template v-if="type == 'jandex_geolocations'">
                <div
                    v-for="(log, filename, index) in jandexGeolocationLogs"
                    :key="index"
                    :id="`jandex-geolocation-log-${index}`"
                >
                    <p>
                        <a
                            class="dropdown-toggle"
                            data-toggle="collapse"
                            aria-expanded="false"
                            :href="`#jandex-geolocation-log-${index}-collapse`"
                            :aria-controls="`jandex-geolocation-log-${index}-collapse`"
                        >
                            {{ filename }}
                        </a>
                    </p>

                    <p
                        :id="`jandex-geolocation-log-${index}-collapse`"
                        class="collapse"
                    >
                        <pre>{{ log }}</pre>
                    </p>
                </div>
            </template>
        </div>
    </div>
</template>

<script>
    import Http from '../services/Http'

    export default {
        data () {
            return {
                type: 'bots',
                botLogs: {},
                jandexGeolocationLogs: {}
            }
        },

        created () {
            new Http({ auth: true }).get('/logs?type=bots')
                .then(({ data }) => this.botLogs = data)

            new Http({ auth: true }).get('/logs?type=jandex_geolocations')
                .then(({ data }) => this.jandexGeolocationLogs = data)
        }
    }
</script>
