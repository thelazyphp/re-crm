module.exports = {
  publicPath: process.env.NODE_ENV === 'production'
    ? process.env.VUE_APP_PUBLIC_PATH
    : '/',

  chainWebpack (config) {
    config.plugin('html').tap(args => {
      args[0].title = process.env.VUE_APP_NAME
      return args
    })
  }
}
