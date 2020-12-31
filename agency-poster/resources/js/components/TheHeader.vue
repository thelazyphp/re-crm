<template>
    <header>
        <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow">
            <router-link
                class="navbar-brand"
                :to="{ name: 'home' }"
            >
                {{ appName }}
            </router-link>

            <button
                type="button"
                class="navbar-toggler"
                data-toggle="collapse"
                data-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent"
                aria-expanded="false"
                :aria-label="$t('components.header.toggle_navigation')"
            >
                <span class="navbar-toggler-icon"></span>
            </button>

            <div
                id="navbarSupportedContent"
                class="collapse navbar-collapse"
            >
                <ul class="navbar-nav mr-auto">
                    <template v-if="currentUser.id">
                        <HeaderNavItem
                            route-name='update'
                            :text="$t('components.header.update')"
                        />

                        <HeaderNavItem
                            route-name='feeds'
                            :text="$t('components.header.feeds')"
                        />

                        <HeaderNavItem
                            route-name='bots'
                            :text="$t('components.header.bots')"
                        />

                        <HeaderNavItem
                            route-name='logs'
                            :text="$t('components.header.logs')"
                        />
                    </template>
                </ul>

                <ul class="navbar-nav ml-auto">
                    <router-link
                        v-if="! currentUser.id"
                        class="btn btn-outline-light my-2 my-lg-0"
                        :to="{ name: 'login' }"
                    >
                        {{ $t('components.header.sign_in') }}
                    </router-link>

                    <template v-else>
                        <li class="nav-item dropdown">
                            <a
                                id="navbarDropdown"
                                href="#"
                                class="nav-link dropdown-toggle"
                                role="button"
                                data-toggle="dropdown"
                                aria-haspopup="true"
                                aria-expanded="false"
                            >
                                {{ currentUser.first_name }} {{ currentUser.last_name }}
                            </a>

                            <div
                                class="dropdown-menu dropdown-menu-right"
                                aria-labelledby="navbarDropdown"
                            >
                                <router-link
                                    class="dropdown-item"
                                    :to="{ name: 'profile' }"
                                >
                                    {{ $t('components.header.profile') }}
                                </router-link>

                                <a
                                    href="#"
                                    class="dropdown-item"
                                    @click="logout"
                                >
                                    {{ $t('components.header.sign_out') }}
                                </a>
                            </div>
                        </li>
                    </template>
                </ul>
            </div>
        </nav>

        <AppAlert
            v-if="alert.show"
            class="mb-0 rounded-0"
            :type="alert.type"
            :message="alert.message"
            @close="$store.commit('alert/CLOSE')"
        />
    </header>
</template>

<script>
    import AppAlert from './AppAlert'
    import HeaderNavItem from './HeaderNavItem'

    import * as AuthService from '../services/auth.service'

    export default {
        components: {
            AppAlert,
            HeaderNavItem
        },

        computed: {
            alert () {
                return this.$store.state.alert
            },

            appName () {
                return process.env.MIX_APP_NAME || 'Laravel'
            }
        },

        methods: {
            logout () {
                AuthService.makeLogout()
                    .then(() => this.$router.push({ name: 'login' }))
                    .catch(error => {
                        console.log(error)
                        this.$store.commit('alert/SHOW', {
                            type: 'danger',
                            message: this.$t('errors.sign_out_error')
                        })
                    })
            }
        }
    }
</script>

<style scoped>
    .navbar-toggler {
        outline: none !important;
    }
</style>
