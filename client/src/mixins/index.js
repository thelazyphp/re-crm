import Vue from 'vue'

Vue.mixin({
  computed: {
    publicPath () {
      return process.env.BASE_URL
    }
  },

  methods: {
    loadScript(src, async = false, type = 'text/javascript') {
      const script = document.createElement('script')

      script.src = src
      script.type = type
      script.async = async

      document.body.appendChild(script)
    }
  }
})
