<template>
  <section class="section-border border-primary">
    <div class="container d-flex flex-column">
      <div class="row align-items-center justify-content-center no-gutters min-vh-100">
        <div class="col-8 col-md-6 col-lg-7 offset-md-1 order-md-2 mt-auto mt-md-0 pt-8 pb-4 py-md-11">
          <img :src="`${publicPath}assets/img/illustrations/illustration-8.png`" alt="..." class="img-fluid">
        </div>
        <div class="col-12 col-md-5 col-lg-4 order-md-1 mb-auto mb-md-0 pb-8 py-md-11">
          <h1 class="mb-0 font-weight-bold text-center">
            Создать аккаунт
          </h1>
          <p class="mb-6 text-center text-muted">
            Заполните форму ниже.
          </p>
          <form class="mb-6" novalidate @submit.prevent="submitForm">
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
              <input id="password" v-model="form.data.password" type="password" class="form-control" :class="{ 'is-invalid': form.errors.password.length }" placeholder="Введите пароль">
              <div v-if="form.errors.password.length" class="invalid-feedback">
                {{ form.errors.password[0] }}
              </div>
            </div>
            <div class="form-group mb-5">
              <label for="passwordConf">
                Подтвердите пароль
              </label>
              <input id="passwordConf" v-model="form.data.password_conf" type="password" class="form-control" :class="{ 'is-invalid': form.errors.password_conf.length }" placeholder="Подтвердите пароль" @paste.prevent>
              <div v-if="form.errors.password_conf.length" class="invalid-feedback">
                {{ form.errors.password_conf[0] }}
              </div>
            </div>
            <button type="submit" class="btn btn-block btn-primary">
              Создать аккаунт
            </button>
          </form>
          <p class="mb-0 font-size-sm text-center text-muted">
            Уже есть аккаунт? <router-link to="/sign-in">Войти</router-link>.
          </p>
        </div>
      </div>
    </div>
  </section>
</template>

<script>
import axios from 'axios'

export default {
  name: 'SignUp',

  data () {
    return {
      form: {
        loading: false,
        data: {
          _trans: 'ru',
          name: '',
          email: '',
          password: '',
          password_conf: ''
        },
        errors: {
          name: [],
          email: [],
          password: [],
          password_conf: []
        }
      }
    }
  },

  watch: {
    ['form.loading'] (value) {
      if (value) {
        //
      } else {
        //
      }
    }
  },

  mounted () {
    document.getElementById('name').focus()
  },

  methods: {
    submitForm () {
      this.form.loading = true

      this.form.errors.name = []
      this.form.errors.email = []
      this.form.errors.password = []
      this.form.errors.password_conf = []

      axios.post('/register', this.form.data).then(() => {
        //
      }).catch(error => {
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
