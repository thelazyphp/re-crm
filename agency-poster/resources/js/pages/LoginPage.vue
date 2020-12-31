<template>
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card shadow">
                <div class="card-header">{{ $t('pages.login.sign_in') }}</div>

                <form
                    action="#"
                    class="card-body"
                    @submit.prevent="login"
                >
                    <div class="text-center">
                        <p class="text-primary">
                            <i class="fas fa-sign-in-alt fa-5x"></i>
                        </p>

                        <p>
                            {{ $t('pages.login.not_registered_yet') }}
                            <br>
                            <router-link :to="{ name: 'register' }">{{ $t('pages.login.register_account') }}</router-link>
                        </p>
                    </div>

                    <div class="form-group">
                        <label for="email">{{ $t('pages.login.email') }}</label>
                        <input
                            id="email"
                            v-model="email"
                            type="email"
                            class="form-control"
                            required
                            autocomplete="email"
                            autofocus
                        >
                    </div>

                    <div class="form-group">
                        <label for="password">{{ $t('pages.login.password') }}</label>
                        <input
                            id="password"
                            v-model="password"
                            type="password"
                            class="form-control"
                            required
                            autocomplete="cur-password"
                        >
                    </div>

                    <div class="d-flex w-100 justify-content-end">
                        <button
                            type="submit"
                            class="btn btn-primary"
                        >
                            {{ $t('buttons.sign_in') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
    import * as authService from '../services/auth.service'

    export default {
        data () {
            return {
                email: '',
                password: ''
            }
        },

        methods: {
            login () {
                authService.makeLogin(this.email, this.password)
                    .then(() => {
                        this.$store.commit('alert/SHOW', {
                            type: 'primary',
                            message: this.$t('messages.signed_in')
                        })

                        this.$router.push({ name: 'home' })
                    })
                    .catch(error => {
                        console.log(error)
                        if (error.response.status == 400) {
                            this.$store.commit('alert/SHOW', {
                                type: 'danger',
                                message: this.$t('errors.invalid_credentials')
                            })
                        } else {
                            this.$store.commit('alert/SHOW', {
                                type: 'danger',
                                message: this.$t('errors.sign_in_error')
                            })
                        }
                    })
            }
        }
    }
</script>
