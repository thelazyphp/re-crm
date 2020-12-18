import Vue from 'vue'

Vue.mixin({
  computed: {
    publicPath () {
      return process.env.BASE_URL
    },

    appName () {
      return process.env.VUE_APP_NAME
    }
  },

  methods: {
    loadScript (src, async = false, type = 'text/javascript') {
      const script = document.createElement('script')

      script.src = src
      script.type = type
      script.async = async

      this.removeExistingScript(src)
      document.body.appendChild(script)
    },

    removeExistingScript(src) {
      const script = document.querySelector(`script[src="${src}"]`)

      if (script) {
        script.remove()
      }
    }
  }
})
