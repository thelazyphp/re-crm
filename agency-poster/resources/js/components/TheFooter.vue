<template>
    <footer>
        <ul class="list-inline text-center text-muted">
            <li class="list-inline-item">&copy; {{ year }}</li>

            <li class="list-inline-item">
                <FooterLanguageSelect/>
            </li>
        </ul>
    </footer>
</template>

<script>
    import FooterLanguageSelect from './FooterLanguageSelect'

    export default {
        components: { FooterLanguageSelect },

        computed: {
            year () {
                return (new Date()).getFullYear()
            }
        }
    }
</script>
