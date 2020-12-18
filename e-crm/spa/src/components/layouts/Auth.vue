<template>
  <div>
    <div class="account-pages mt-5 mb-5">
      <router-view/>
    </div>
    <footer class="footer footer-alt">
      {{ year }} © {{ appName }}
    </footer>
  </div>
</template>

<script>
export default {
  name: 'AuthLayout',

  computed: {
    year () {
      return new Date().getFullYear()
    }
  },

  beforeMount () {
    document.body.className = 'authentication-bg'
    document.body.setAttribute('data-layout-config', JSON.stringify({
      darkMode: false
    }))
  }
}
</script>
