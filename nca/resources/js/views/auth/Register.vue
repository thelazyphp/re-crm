<template>
    <div class="row">
        <div class="col-lg-5 d-none d-lg-block bg-register-image"></div>
        <div class="col-lg-7">
            <div class="p-5">
                <div class="text-center">
                    <h1 class="h4 text-gray-900 mb-4">
                        Создать аккаунт
                    </h1>
                </div>

                <form
                    class="user"
                    novalidate
                    @submit.prevent="submitForm"
                >
                    <fieldset :disabled="form.submitting">
                        <div class="form-group row">
                            <div class="col-sm-6 mb-3 mb-sm-0">
                                <input
                                    id="firstName"
                                    v-model="form.data.first_name"
                                    type="text"
                                    placeholder="Имя"
                                    class="form-control form-control-user"
                                    :class="{ 'is-invalid': form.errors.first_name.length }"
                                />

                                <div
                                    v-if="form.errors.first_name.length"
                                    class="invalid-feedback"
                                >
                                    {{ form.errors.first_name[0] }}
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <input
                                    v-model="form.data.last_name"
                                    type="text"
                                    placeholder="Фамилия"
                                    class="form-control form-control-user"
                                    :class="{ 'is-invalid': form.errors.last_name.length }"
                                />

                                <div
                                    v-if="form.errors.last_name.length"
                                    class="invalid-feedback"
                                >
                                    {{ form.errors.last_name[0] }}
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <input
                                v-model="form.data.email"
                                type="email"
                                placeholder="Email"
                                class="form-control form-control-user"
                                :class="{ 'is-invalid': form.errors.email.length }"
                            />

                            <div
                                v-if="form.errors.email.length"
                                class="invalid-feedback"
                            >
                                {{ form.errors.email[0] }}
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-6 mb-3 mb-sm-0">
                                <input
                                    v-model="form.data.password"
                                    type="password"
                                    placeholder="Пароль"
                                    class="form-control form-control-user"
                                    :class="{ 'is-invalid': form.errors.password.length }"
                                />

                                <div
                                    v-if="form.errors.password.length"
                                    class="invalid-feedback"
                                >
                                    {{ form.errors.password[0] }}
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <input
                                    v-model="form.data.password_confirmation"
                                    type="password"
                                    placeholder="Подтвердите пароль"
                                    class="form-control form-control-user"
                                    :class="{ 'is-invalid': form.errors.password_confirmation.length }"
                                    @paste.prevent
                                />

                                <div
                                    v-if="form.errors.password_confirmation.length"
                                    class="invalid-feedback"
                                >
                                    {{ form.errors.password_confirmation[0] }}
                                </div>
                            </div>
                        </div>

                        <button
                            type="submit"
                            class="btn btn-primary btn-user btn-block"
                        >
                            Создать аккаунт
                        </button>

                        <hr/>

                        <a
                            href="#"
                            class="btn btn-google btn-user btn-block"
                        >
                            <i class="fab fa-google fa-fw"></i> Создать с помощью Google
                        </a>

                        <a
                            href="#"
                            class="btn btn-facebook btn-user btn-block"
                        >
                            <i class="fab fa-facebook-f fa-fw"></i> Создать с помощью Facebook
                        </a>
                    </fieldset>
                </form>

                <hr/>

                <div class="text-center">
                    <router-link
                        :to="{ name: 'login' }"
                        class="small"
                    >
                        Уже есть аккаунт? Войти
                    </router-link>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    name: 'RegisterPage',

    data () {
        return {
            form: {
                submitting: false,
                data: {
                    first_name: '',
                    last_name: '',
                    email: '',
                    password: '',
                    password_confirmation: ''
                },
                errors: {
                    first_name: [],
                    last_name: [],
                    email: [],
                    password: [],
                    password_confirmation: []
                }
            }
        }
    },

    mounted () {
        document.getElementById('firstName').focus();
    },

    methods: {
        clearFormErrors () {
            for (const key in this.form.errors) {
                this.form.errors[key] = [];
            }
        },

        async submitForm () {
            this.clearFormErrors();
            this.form.submitting = true;

            try {
                await axios.get('/sanctum/csrf-cookie');
                await axios.post('/register', this.form.data);
                this.form.submitting = false;
                this.$store.commit('SET_IS_AUTH', true);

                this.$router.push({
                    name: 'dashboard'
                });
            } catch (error) {
                console.log(error);
                this.form.submitting = false;

                if (typeof error.response !== 'undefined') {
                    if (error.response.status === 422) {
                        Object.assign(this.form.errors, error.response.data.errors);
                    }
                }
            }
        }
    }
};
</script>

<style scoped>
    .bg-register-image {
        background-image: url('/nca/theme/img/covers/register.jpg') !important;
    }
</style>
