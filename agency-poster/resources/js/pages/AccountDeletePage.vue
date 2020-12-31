<template>
    <div class="card shadow">
        <div class="card-header">{{ $t('pages.account_delete.delete_account') }}</div>

        <form
            action="#"
            class="card-body"
            @submit.prevent="deleteAccount"
        >
            <p class="text-center text-primary">
                <i class="fas fa-user-slash fa-5x"></i>
            </p>

            <label for="confirmation">
                {{ $t('pages.account_delete.type') }}
                <span class="text-danger">delete my account</span>:
            </label>

            <input
                id="confirmation"
                v-model="confirmation"
                type="text"
                class="form-control"
                required
                @paste.prevent
            >

            <div
                v-if="isConfirmed"
                class="d-flex w-100 justify-content-end mt-3"
            >
                <button
                    type="submit"
                    class="btn btn-danger"
                >
                    {{ $t('buttons.delete_account') }}
                </button>
            </div>
        </form>
    </div>
</template>

<script>
    import * as AuthService from '../services/auth.service'
    import * as UsersService from '../services/users.service'

    export default {
        data () {
            return {
                confirmation: ''
            }
        },

        computed: {
            isConfirmed () {
                return this.confirmation == 'delete my account'
            }
        },

        methods: {
            deleteAccount () {
                if (this.isConfirmed) {
                    UsersService.deleteAccount()
                        .then(() => {
                            AuthService.removeAuthData()

                            this.$store.commit('alert/SHOW', {
                                type: 'primary',
                                message: this.$t('messages.account_deleted')
                            })

                            this.$router.push({ name: 'login' })
                        })
                        .catch(error => {
                            console.log(error)
                            this.$store.commit('alert/SHOW', {
                                type: 'danger',
                                message: this.$t('errors.delete_account_error')
                            })
                        })
                }
            }
        }
    }
</script>
