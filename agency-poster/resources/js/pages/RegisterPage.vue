<template>
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card shadow">
                <div class="card-header">{{ $t('pages.register.sign_up') }}</div>

                <form
                    action="#"
                    class="card-body"
                    @submit.prevent="register"
                >
                    <div class="text-center">
                        <p class="text-primary">
                            <i class="fas fa-user fa-5x"></i>
                        </p>

                        <p>
                            {{ $t('pages.register.already_registered') }}
                            <br>
                            <router-link :to="{ name: 'login' }">{{ $t('pages.register.sign_in_account') }}</router-link>
                        </p>
                    </div>

                    <div class="form-group">
                        <label for="first-name">{{ $t('pages.register.first_name') }}</label>
                        <input
                            id="first-name"
                            v-model="first_name"
                            type="text"
                            class="form-control"
                            required
                            autocomplete="given-name"
                            autofocus
                            :class="{ 'is-invalid': errors.first_name }"
                        >
                        <div class="invalid-feedback">{{ errors.first_name }}</div>
                    </div>

                    <div class="form-group">
                        <label for="last-name">{{ $t('pages.register.last_name') }}</label>
                        <input
                            id="last-name"
                            v-model="last_name"
                            type="text"
                            class="form-control"
                            required
                            autocomplete="family-name"
                            :class="{ 'is-invalid': errors.last_name }"
                        >
                        <div class="invalid-feedback">{{ errors.last_name }}</div>
                    </div>

                    <div class="form-group">
                        <label for="email">{{ $t('pages.register.email') }}</label>
                        <input
                            id="email"
                            v-model="email"
                            type="email"
                            class="form-control"
                            required
                            autocomplete="email"
                            :class="{ 'is-invalid': errors.email }"
                        >
                        <div class="invalid-feedback">{{ errors.email }}</div>
                    </div>

                    <div class="form-group">
                        <label for="password">{{ $t('pages.register.password') }}</label>
                        <input
                            id="password"
                            v-model="password"
                            type="password"
                            class="form-control"
                            required
                            autocomplete="new-password"
                            :class="{ 'is-invalid': errors.password }"
                        >
                        <div class="invalid-feedback">{{ errors.password }}</div>
                    </div>

                    <div class="form-group">
                        <label for="password-confirmation">{{ $t('pages.register.confirm_password') }}</label>
                        <input
                            id="password-confirmation"
                            v-model="password_confirmation"
                            type="password"
                            class="form-control"
                            required
                            autocomplete="new-password"
                            :class="{ 'is-invalid': errors.password_confirmation }"
                            @paste.prevent
                        >
                        <div class="invalid-feedback">{{ errors.password_confirmation }}</div>
                    </div>

                    <div class="d-flex w-100 justify-content-end">
                        <button
                            type="submit"
                            class="btn btn-primary"
                        >
                            {{ $t('buttons.sign_up') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
    import * as AuthService from '../services/auth.service'

    export default {
        data () {
            return {
                first_name: '',
                last_name: '',
                email: '',
                password: '',
                password_confirmation: '',

                errors: {
                    first_name: '',
                    last_name: '',
                    email: '',
                    password: '',
                    password_confirmation: ''
                }
            }
        },

        methods: {
            register () {
                this.clearErrors()

                AuthService.makeRegister(this.first_name, this.last_name, this.email, this.password, this.password_confirmation)
                    .then(() => {
                        AuthService.makeLogin(this.email, this.password)
                            .then(() => {
                                this.$store.commit('alert/SHOW', {
                                    type: 'primary',
                                    message: this.$t('messages.signed_up')
                                })

                                this.$router.push({ name: 'home' })
                            })
                            .catch(error => {
                                console.log(error)
                                this.$store.commit('alert/SHOW', {
                                    type: 'danger',
                                    message: this.$t('errors.sign_in_error')
                                })
                            })
                    })
                    .catch(error => {
                        console.log(error)
                        if (error.response.status == 422) {
                            this.setErrors(error.response.data.errors)
                        } else {
                            this.$store.commit('alert/SHOW', {
                                type: 'danger',
                                message: this.$t('errors.sign_up_error')
                            })
                        }
                    })
            }
        }
    }
</script>
