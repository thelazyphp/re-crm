<template>
    <div class="dropup">
        <a
            href="#"
            class="dropdown-toggle"
            data-toggle="dropdown"
            aria-haspopup="true"
            aria-expanded="false"
        >
            {{ $t('language', currentLocale) }}
        </a>

        <div class="dropdown-menu dropdown-menu-right">
            <a
                v-for="locale in locales"
                :key="locale"
                href="#"
                class="dropdown-item"
                :class="{ 'active': locale == currentLocale }"
                @click="setLocale(locale)"
            >
                {{ $t('language', locale) }}
            </a>
        </div>
    </div>
</template>

<script>
    export default {
        computed: {
            currentLocale () {
                return this.$i18n.locale
            },

            locales () {
                return Object.keys(this.$i18n.messages)
            }
        },

        methods: {
            setLocale (locale) {
                this.$i18n.locale = locale
            }
        }
    }
</script>
