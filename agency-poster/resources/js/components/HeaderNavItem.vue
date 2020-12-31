<template>
    <li
        class="nav-item"
        :class="{ 'active': isActive }"
    >
        <router-link
            class="nav-link"
            :to="{ name: routeName }"
        >
            {{ text }}

            <span
                v-if="isActive"
                class="sr-only"
            >
                ({{ $t('components.nav_item.current') }})
            </span>
        </router-link>
    </li>
</template>

<script>
    export default {
        props: {
            text: String,
            routeName: String
        },

        computed: {
            isActive () {
                return this.$route.name && this.$route.name.startsWith(this.routeName)
            }
        }
    }
</script>
