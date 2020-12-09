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
          <form class="mb-6">
            <div class="form-group">
              <label for="name">
                Имя
              </label>
              <input id="name" type="text" class="form-control" placeholder="Введите имя">
            </div>
            <div class="form-group">
              <label for="email">
                Email
              </label>
              <input id="email" type="email" class="form-control" placeholder="Введите email">
            </div>
            <div class="form-group">
              <label for="password">
                Пароль
              </label>
              <input id="password" type="password" class="form-control" placeholder="Введите пароль">
            </div>
            <div class="form-group mb-5">
              <label for="passwordConf">
                Подтвердите пароль
              </label>
              <input id="passwordConf" type="password" class="form-control" placeholder="Подтвердите пароль" @paste.prevent>
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
export default {
  name: 'SignUp',

  mounted () {
    document.getElementById('name').focus()
  }
}
</script>
