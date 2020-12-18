<template>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-5">
        <div class="card">
          <div class="card-header pt-4 pb-4 text-center bg-primary">
            <router-link to="/">
              <span>
                <img src="@/assets/images/logo.png" alt="..." height="18">
              </span>
            </router-link>
          </div>
          <div class="card-body p-4">
            <div class="text-center w-75 m-auto">
              <h4 class="text-dark-50 text-center mt-0 font-weight-bold">
                Создать аккаунт
              </h4>
              <p class="text-muted mb-4">
                Заполните форму ниже
              </p>
            </div>
            <form novalidate @submit.prevent="submitForm">
              <fieldset :disabled="form.loading">
                <div class="form-group">
                  <label for="name">
                    Имя
                  </label>
                  <input id="name" v-model="form.data.name" type="text" class="form-control" :class="{ 'is-invalid': form.errors.name.length }" placeholder="Введите имя">
                  <div v-if="form.errors.name.length" class="invalid-feedback">
                    {{ form.errors.name[0] }}
                  </div>
                </div>
                <div class="form-group">
                  <label for="email">
                    Email
                  </label>
                  <input id="email" v-model="form.data.email" type="email" class="form-control" :class="{ 'is-invalid': form.errors.email.length }" placeholder="Введите email">
                  <div v-if="form.errors.email.length" class="invalid-feedback">
                    {{ form.errors.email[0] }}
                  </div>
                </div>
                <div class="form-group">
                  <label for="password">
                    Пароль
                  </label>
                  <div class="input-group input-group-merge">
                    <input id="password" v-model="form.data.password" type="password" class="form-control" :class="{ 'is-invalid': form.errors.password.length }" placeholder="Введите пароль">
                    <div class="input-group-append" data-password="false">
                      <div class="input-group-text">
                        <span class="password-eye"></span>
                      </div>
                    </div>
                  </div>
                  <div v-if="form.errors.password.length" class="invalid-feedback">
                    {{ form.errors.password[0] }}
                  </div>
                </div>
                <div class="form-group mb-3">
                  <label for="passwordConfirmation">
                    Подтвердите пароль
                  </label>
                  <div class="input-group input-group-merge">
                    <input id="passwordConfirmation" v-model="form.data.password_confirmation" type="password" class="form-control" :class="{ 'is-invalid': form.errors.password_confirmation.length }" placeholder="Подтвердите пароль" @paste.prevent>
                    <div class="input-group-append" data-password="false">
                      <div class="input-group-text">
                        <span class="password-eye"></span>
                      </div>
                    </div>
                  </div>
                  <div v-if="form.errors.password_confirmation.length" class="invalid-feedback">
                    {{ form.errors.password_confirmation[0] }}
                  </div>
                </div>
                <div class="form-group mb-0 text-right">
                  <button type="submit" class="btn btn-primary">
                    <span v-if="form.loading" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                    Создать аккаунт
                  </button>
                </div>
              </fieldset>
            </form>
          </div>
        </div>
        <div class="row mt-3">
          <div class="col-12 text-center">
            <p class="text-muted">
              Уже есть аккаунт? <router-link to="/login" class="text-muted ml-1"><b>Войти</b></router-link>
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { mapActions } from 'vuex'

export default {
  name: 'RegisterPage',

  data () {
    return {
      form: {
        loading: false,
        data: {
          name: '',
          email: '',
          password: '',
          password_confirmation: ''
        },
        errors: {
          name: [],
          email: [],
          password: [],
          password_confirmation: []
        }
      }
    }
  },

  mounted () {
    document.getElementById('name').focus()

    this.$nextTick(() => {
      this.loadScript(`${this.publicPath}assets/js/vendor.min.js`)
      this.loadScript(`${this.publicPath}assets/js/app.min.js`)
    })
  },

  methods: {
    ...mapActions([
      'register'
    ]),

    submitForm () {
      this.form.loading = true

      this.form.errors.name = []
      this.form.errors.email = []
      this.form.errors.password = []
      this.form.errors.password_confirmation = []

      this.register(this.form.data).then(() => this.$router.push('/'))
        .catch(error => {
          if (error.response.status === 422) {
            Object.assign(
              this.form.errors,
              error.response.data.errors
            )
          }
        }).finally(() => this.form.loading = false)
    }
  }
}
</script>
