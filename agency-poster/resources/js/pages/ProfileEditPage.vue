<template>
    <div class="card shadow">
        <div class="card-header">{{ $t('pages.profile_edit.edit_profile') }}</div>

        <form
            action="#"
            class="card-body"
            @submit.prevent="editProfile"
        >
            <p class="text-center text-primary">
                <i class="fas fa-user-edit fa-5x"></i>
            </p>

            <div class="form-group">
                <label for="first-name">{{ $t('pages.profile_edit.first_name') }}</label>
                <input
                    id="first-name"
                    v-model="first_name"
                    type="text"
                    class="form-control"
                    autocomplete="given-name"
                    autofocus
                    :class="{ 'is-invalid': errors.first_name }"
                >
                <div class="invalid-feedback">{{ errors.first_name }}</div>
            </div>

            <div class="form-group">
                <label for="last-name">{{ $t('pages.profile_edit.last_name') }}</label>
                <input
                    id="last-name"
                    v-model="last_name"
                    type="text"
                    class="form-control"
                    autocomplete="family-name"
                    :class="{ 'is-invalid': errors.last_name }"
                >
                <div class="invalid-feedback">{{ errors.last_name }}</div>
            </div>

            <div class="form-group">
                <label for="email">{{ $t('pages.profile_edit.email') }}</label>
                <input
                    id="email"
                    v-model="email"
                    type="email"
                    class="form-control"
                    autocomplete="email"
                    :class="{ 'is-invalid': errors.email }"
                >
                <div class="invalid-feedback">{{ errors.email }}</div>
            </div>

            <div class="form-group">
                <label for="password">{{ $t('pages.profile_edit.password') }}</label>
                <input
                    id="password"
                    v-model="password"
                    type="password"
                    class="form-control"
                    autocomplete="cur-password"
                    :class="{ 'is-invalid': errors.password }"
                >
                <div class="invalid-feedback">{{ errors.password }}</div>
            </div>

            <div class="form-group">
                <label for="new-password">{{ $t('pages.profile_edit.new_password') }}</label>
                <input
                    id="new-password"
                    v-model="new_password"
                    type="password"
                    class="form-control"
                    autocomplete="new-password"
                    :class="{ 'is-invalid': errors.new_password }"
                >
                <div class="invalid-feedback">{{ errors.new_password }}</div>
            </div>

            <div class="form-group">
                <label for="new-password-confirmation">{{ $t('pages.profile_edit.confirm_password') }}</label>
                <input
                    id="new-password-confirmation"
                    v-model="new_password_confirmation"
                    type="password"
                    class="form-control"
                    autocomplete="new-password"
                    :class="{ 'is-invalid': errors.new_password_confirmation }"
                    @paste.prevent
                >
                <div class="invalid-feedback">{{ errors.new_password_confirmation }}</div>
            </div>

            <div class="d-flex w-100 justify-content-end">
                <div>
                    <button
                        type="submit"
                        class="btn btn-primary"
                    >
                        {{ $t('buttons.edit_profile') }}
                    </button>

                    <router-link
                        class="btn btn-danger"
                        :to="{ name: 'account.delete' }"
                    >
                        {{ $t('buttons.delete_account') }}
                    </router-link>
                </div>
            </div>
        </form>
    </div>
</template>

<script>
    import * as UsersService from '../services/users.service'

    export default {
        data () {
            return {
                first_name: this.$store.state.users.current.first_name,
                last_name: this.$store.state.users.current.last_name,
                email: this.$store.state.users.current.email,
                password: '',
                new_password: '',
                new_password_confirmation: '',

                errors: {
                    first_name: '',
                    last_name: '',
                    email: '',
                    password: '',
                    new_password: '',
                    new_password_confirmation: ''
                }
            }
        },

        methods: {
            editProfile () {
                this.clearErrors()

                UsersService.editProfile(this)
                    .then(() => {
                        this.$store.dispatch('users/getCurrent')
                            .then(() => {
                                this.$store.commit('alert/SHOW', {
                                    type: 'primary',
                                    message: this.$t('messages.profile_edited')
                                })
                            })
                            .catch(error => {
                                console.log(error)
                                this.$store.commit('alert/SHOW', {
                                    type: 'danger',
                                    message: this.$t('get_current_user_error')
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
                                message: this.$t('errors.edit_profile_error')
                            })
                        }
                    })
            }
        }
    }
</script>
