<template>
  <div>
    <div class="wrapper">
      <div class="content-page">
        <div class="content">
          <div class="navbar-custom topnav-navbar topnav-navbar-dark">
            <div class="container-fluid">
              <router-link to="/" class="topnav-logo">
                <span class="topnav-logo-lg">
                  <img src="@/assets/images/logo-light.png" alt="..." height="16">
                </span>
                <span class="topnav-logo-sm">
                  <img src="@/assets/images/logo_sm_dark.png" alt="..." height="16">
                </span>
              </router-link>
              <ul class="list-unstyled topbar-right-menu float-right mb-0">
                <li class="dropdown notification-list">
                  <a href="#" class="nav-link dropdown-toggle arrow-none" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="dripicons-bell noti-icon"></i>
                    <span class="noti-icon-badge"></span>
                  </a>
                  <div class="dropdown-menu dropdown-menu-right dropdown-menu-animated dropdown-lg">
                    <div class="dropdown-item noti-title">
                      <h5 class="m-0">
                        <span class="float-right">
                          <a href="#" role="button" class="text-dark">
                            <small>
                              Отчистить все
                            </small>
                          </a>
                        </span>
                        Уведомления
                      </h5>
                    </div>
                    <div style="max-height: 230px;" data-simplebar>
                      <a href="#" class="dropdown-item notify-item">
                        <div class="notify-icon bg-info">
                          <i class="mdi mdi-account-plus"></i>
                        </div>
                        <p class="notify-details">
                          Добавлен новый сотрудник
                          <small class="text-muted">
                            5 часов назад
                          </small>
                        </p>
                      </a>
                    </div>
                    <a href="#" role="button" class="dropdown-item text-center text-primary notify-item notify-all">
                      Посмотреть все
                    </a>
                  </div>
                </li>
                <li class="dropdown notification-list">
                  <a v-if="currentUser" href="#" role="button" class="nav-link dropdown-toggle nav-user arrow-none mr-0" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span class="account-user-avatar">
                      <img src="@/assets/images/users/avatar-1.jpg" alt="..." class="rounded-circle">
                    </span>
                    <span>
                      <span class="account-user-name">
                        {{ currentUser.name }} {{ currentUser.last_name }}
                      </span>
                      <span class="account-position">
                        {{ currentUser.email }}
                      </span>
                    </span>
                  </a>
                  <div class="dropdown-menu dropdown-menu-right dropdown-menu-animated topbar-dropdown-menu profile-dropdown">
                    <div class=" dropdown-header noti-title">
                      <h6 class="text-overflow m-0">
                        Добро пожаловать!
                      </h6>
                    </div>
                    <a href="#" class="dropdown-item notify-item">
                      <i class="mdi mdi-account-circle mr-1"></i>
                      <span>
                        Аккаунт
                      </span>
                    </a>
                    <a href="#" role="button" class="dropdown-item notify-item" @click.prevent="logout">
                      <i class="mdi mdi-logout mr-1"></i>
                      <span>
                        Выйти
                      </span>
                    </a>
                    <a href="#" role="button" class="dropdown-item notify-item" @click.prevent>
                      <i class="mdi mdi-lock-outline mr-1"></i>
                      <span>
                        Заблокировать
                      </span>
                    </a>
                  </div>
                </li>
              </ul>
              <a href="#" class="navbar-toggle" role="button" data-toggle="collapse" data-target="#topnavMenuContent">
                <div class="lines">
                  <span></span>
                  <span></span>
                  <span></span>
                </div>
              </a>
            </div>
          </div>
          <div class="topnav shadow-sm">
            <div class="container-fluid">
              <nav class="navbar navbar-light navbar-expand-lg topnav-menu">
                <div id="topnavMenuContent" class="collapse navbar-collapse">
                  <ul class="navbar-nav">
                    <li class="nav-item">
                      <a href="#" class="nav-link">
                        <i class="uil-dashboard mr-1"></i>
                        Панель управления
                      </a>
                    </li>
                    <li class="nav-item dropdown">
                      <a href="#" role="button" class="nav-link dropdown-toggle arrow-none" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="uil-copy-alt mr-1"></i>
                        База БТИ
                        <div class="arrow-down"></div>
                      </a>
                      <div class="dropdown-menu">
                        <a href="#" class="dropdown-item">Просмотр</a>
                        <a href="#" class="dropdown-item">Обновление</a>
                      </div>
                    </li>
                  </ul>
                </div>
              </nav>
            </div>
          </div>
          <div class="container-fluid">
            <router-view/>
          </div>
        </div>
        <footer class="footer">
          <div class="container-fluid">
            <div class="row">
              <div class="col-md-6">
                {{ year }} © {{ appName }}
              </div>
              <div class="col-md-6">
                <div class="text-md-right footer-links d-none d-md-block">
                  <a href="#">О нас</a>
                  <a href="#">Поддержка</a>
                  <a href="#">Связаться с нами</a>
                </div>
              </div>
            </div>
          </div>
        </footer>
      </div>
    </div>
  </div>
</template>

<script>
import { mapState } from 'vuex'

export default {
  name: 'DefaultLayout',

  computed: {
    ...mapState({
      currentUser: state => state.currentUser
    }),

    year () {
      return new Date().getFullYear()
    }
  },

  beforeMount () {
    document.body.className = ''
    // document.body.className = 'loading'
    document.body.setAttribute('data-layout', 'topnav')
    document.body.setAttribute('data-layout-config', JSON.stringify({
      'layoutBoxed':false,
      'darkMode':false,
      'showRightSidebarOnStart': true
    }))
  },

  mounted () {
    this.$nextTick(() => {
      this.loadScript(`${this.publicPath}assets/js/vendor.min.js`)
      this.loadScript(`${this.publicPath}assets/js/app.min.js`)
    })
  },

  methods: {
    logout () {
      this.$store.dispatch('logout').then(() => this.$router.push('/login'))
    }
  }
}
</script>
