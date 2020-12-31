<script>
export default {
  data () {
    return {
      name: null,
      email: null,
      password: null,
      newPassword: null,
      newPasswordConfirmation: null,
      removeAccountConfirmation: null
    }
  },

  computed: {
    title () {
      return this.$route.meta.title
    },

    user () {
      return this.$store.state.users.current
    },

    removeAccountConfirmed () {
      return this.removeAccountConfirmation == 'удалить мой аккаунт'
    }
  },

  created () {
    this.name = this.user.name
    this.email = this.user.email
  },

  methods: {
    async removeAccount () {
      if (this.removeAccountConfirmed) {
        await this.$store.dispatch('users/removeCurrent')
        this.$store.commit('auth/removeToken')
        this.$router.push('/sign-in')
      }
    },

    async signOut () {
      await this.$store.dispatch('auth/signOut')
      this.$router.push('/sign-in')
    },

    async updateProfile () {
      let data = {
        name: this.name,
        email: this.email
      }

      if (this.newPassword) {
        data['password'] = this.password
        data['new_password'] = this.newPassword
        data['new_password_confirmation'] = this.newPasswordConfirmation
      }

      await this.$store.dispatch('users/updateCurrent', data)
    }
  }
}
</script>

<template>
  <div class="container">
    <div class="mx-auto" style="max-width: 500px">
      <h1 class="my-5">{{ title }}</h1>
      <button type="button" class="btn mb-3 btn-primary" @click="signOut"><i class="fas fa-sign-out-alt"></i> Выйти из аккаунта</button>
      <form @submit.prevent="updateProfile">
        <div class="form-group">
          <label for="name">Имя</label>
          <input id="name" v-model="name" type="text" class="form-control" placeholder="Имя" required>
        </div>
        <div class="form-group">
          <label for="email">E-Mail</label>
          <input id="email" v-model="email" type="email" class="form-control" placeholder="E-Mail" required>
        </div>
        <p class="lead">Изменить пароль</p>
        <div class="form-group">
          <label for="password">Текущий пароль</label>
          <input id="password" v-model="password" type="password" :required="newPassword ? true : false" class="form-control" placeholder="Текущий пароль">
        </div>
        <div class="form-group">
          <label for="newPassword">Новый пароль</label>
          <input id="newPassword" v-model="newPassword" type="password" class="form-control" placeholder="Новый пароль">
        </div>
        <div class="form-group">
          <label for="newPasswordConfirmation">Подтвердите новый пароль</label>
          <input id="newPasswordConfirmation" v-model="newPasswordConfirmation" type="password" :required="newPassword ? true : false" class="form-control" placeholder="Подтвердите новый пароль" @paste.prevent>
        </div>
        <div class="mb-3 text-right">
          <button type="submit" class="btn btn-primary">Сохранить</button>
        </div>
      </form>
      <form @submit.prevent="removeAccount">
        <p class="lead">Удалить аккаунт</p>
        <div class="form-group">
          <label for="removeAccountConfirmation">Введите <strong class="text-danger">удалить мой аккаунт</strong></label>
          <input id="removeAccountConfirmation" v-model="removeAccountConfirmation" type="text" class="form-control" :class="{ 'is-invalid': removeAccountConfirmed }" required @paste.prevent>
          <div class="invalid-feedback">Ваш аккаунт будет удален навсегда. Отменить это действие будет невозможно.</div>
        </div>
        <div class="mb-3 text-right">
          <button type="submit" class="btn btn-danger">Удалить аккаунт</button>
        </div>
      </form>
    </div>
  </div>
</template>
