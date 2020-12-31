<script>
export default {
  data () {
    return {
      loading: false,
      email: null,
      password: null
    }
  },

  methods: {
    async signIn() {
      this.loading = true

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
    <div class="card-header">Вход</div>
    <div class="card-body">
      <form @submit.prevent="signIn">
        <div class="form-group">
          <label for="email">E-Mail</label>
          <input id="email" v-model="email" type="email" class="form-control" placeholder="E-Mail" required autofocus autocomplete="email">
        </div>
        <div class="form-group">
          <label for="password">Пароль</label>
          <input id="password" v-model="password" type="password" class="form-control" placeholder="Пароль" required autocomplete="cur-password">
        </div>
        <button type="submit" class="btn btn-lg btn-block btn-primary">Войти</button>
      </form>
    </div>
  </div>
</template>

<style scoped>
.card {
  width: 400px;
}
</style>
