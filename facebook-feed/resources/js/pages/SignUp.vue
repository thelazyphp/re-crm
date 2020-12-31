<script>
export default {
  data () {
    return {
      loading: false,
      name: null,
      email: null,
      password: null,
      passwordConfirmation: null
    }
  },

  methods: {
    async signUp () {
      this.loading = true

      await this.$http.post('/auth/register', {
        name: this.name,
        email: this.email,
        password: this.password,
        password_confirmation: this.passwordConfirmation
      })

      await this.$store.dispatch('auth/signIn', {
        username: this.email,
        password: this.password
      })

      this.loading = false
      this.$router.push('/')
    }
  }
}
</script>

<template>
  <div v-if="loading" class="text-center">
    <div class="spinner-border text-white" role="status">
      <span class="sr-only">Загрузка...</span>
    </div>
  </div>
  <div v-else class="card">
    <div class="card-header">Регистрация</div>
    <div class="card-body">
      <div v-if="loading" class="text-center">
        <div class="spinner-border text-primary" role="status">
          <span class="sr-only">Загрузка...</span>
        </div>
      </div>
      <form @submit.prevent="signUp">
        <div class="form-group">
          <label for="name">Имя</label>
          <input id="name" v-model="name" type="text" class="form-control" placeholder="Имя" required autofocus>
        </div>
        <div class="form-group">
          <label for="email">E-Mail</label>
          <input id="email" v-model="email" type="email" class="form-control" placeholder="E-Mail" required autocomplete="email">
        </div>
        <div class="form-group">
          <label for="password">Пароль</label>
          <input id="password" v-model="password" type="password" class="form-control" placeholder="Пароль" required autocomplete="new-password">
        </div>
        <div class="form-group">
          <label for="passwordConfirmation">Подтвердите пароль</label>
          <input id="passwordConfirmation" v-model="passwordConfirmation" type="password" class="form-control" placeholder="Подтвердите пароль" required autocomplete="new-password" @paste.prevent>
        </div>
        <button type="submit" class="btn btn-lg btn-block btn-primary">Регистрация</button>
      </form>
    </div>
  </div>
</template>

<style scoped>
.card {
  width: 400px;
}
</style>
