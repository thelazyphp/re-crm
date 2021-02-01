<template>
    <div class="row">
		<div class="col-lg-6 d-none d-lg-block bg-login-image"></div>
		<div class="col-lg-6">
		   <div class="p-5">
                <div class="text-center">
                    <h1 class="h4 text-gray-900 mb-4">
                        Войти
                    </h1>
                </div>

                <form
                    class="user"
                    novalidate
                    @submit.prevent="submitForm"
                >
                    <fieldset :disabled="form.submitting">
                        <div class="form-group">
                            <input
                                id="email"
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
                        <div class="form-group">
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

                        <button
                            type="submit"
                            class="btn btn-primary btn-user btn-block"
                        >
                            Войти
                        </button>

                        <hr/>

                        <a
                            href="#"
                            class="btn btn-google btn-user btn-block"
                        >
                            <i class="fab fa-google fa-fw"></i> Войти с помощью Google
                        </a>

                        <a
                            href="#"
                            class="btn btn-facebook btn-user btn-block"
                        >
                            <i class="fab fa-facebook fa-fw"></i> Войти с помощью Facebook
                        </a>
                    </fieldset>
                </form>

                <hr/>

                <div class="text-center">
                    <router-link
                        :to="{ name: 'register' }"
                        class="small"
                    >
                        Еще нет аккаунта? Создать
                    </router-link>
                </div>
            </div>
		</div>
	</div>
</template>

<script>
import axios from 'axios';

export default {
    name: 'LoginPage',

    data () {
        return {
            form: {
                submitting: false,
                data: {
                    email: '',
                    password: ''
                },
                errors: {
                    email: [],
                    password: []
                }
            }
        }
    },

    mounted () {
        document.getElementById('email').focus();
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
                await axios.post('/login', this.form.data);
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
    .bg-login-image {
        background-image: url('/nca/theme/img/covers/login.jpg') !important;
    }
</style>
