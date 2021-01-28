<template>
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
                <fieldset :disabled="form.loading">
				    <div class="form-group">
				    	<input
                            id="email"
                            v-model="form.data.email"
				    		type="email"
				    		class="form-control form-control-user"
                            :class="{ 'is-invalid': form.errors.email.length > 0 }"
				    		placeholder="Email"
				    	/>

                        <div
                            v-if="form.errors.email.length > 0"
                            class="invalid-feedback"
                        >
                            {{ form.errors.email[0] }}
                        </div>
				    </div>
				    <div class="form-group">
				    	<input
                            v-model="form.data.password"
				    		type="password"
				    		class="form-control form-control-user"
                            :class="{ 'is-invalid': form.errors.password.length > 0 }"
				    		placeholder="Пароль"
				    	/>

                        <div
                            v-if="form.errors.password.length > 0"
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
                </fieldset>
			</form>
		</div>
	</div>
</template>

<script>
import axios from 'axios';

import {
    SET_IS_AUTH,
} from '../store/mutation-types';

export default {
    name: 'LoginPage',

    data () {
        return {
            form: {
                loading: false,
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
            this.form.loading = true;

            try {
                await axios.get('/sanctum/scrf-cookie');
                await axios.post('/login', this.form.data);
                this.$store.commit(SET_IS_AUTH, true);
                this.$router.push('/');
            } catch (error) {
                console.log(error);

                if (typeof error.response !== 'undefined') {
                    if (error.response.status === 422) {
                        Object.assign(
                            this.form.errors,
                            error.response.data.errors
                        );
                    }
                }
            } finally {
                this.form.loading = false;
            }
        }
    }
};
</script>
