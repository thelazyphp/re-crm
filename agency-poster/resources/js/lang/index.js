import Vue from 'vue'
import VueI18n from 'vue-i18n'

// import messages
import ru_RU from './ru-RU.json'
import en_US from './en-US.json'

Vue.use(VueI18n)

export default new VueI18n({
    locale: process.env.MIX_LOCALE || 'ru-RU',
    fallbackLocale: process.env.MIX_FALLBACK_LOCALE || 'en-US',

    messages: {
        'ru-RU': ru_RU,
        'en-US': en_US
    }
})
