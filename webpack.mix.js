let mix = require('laravel-mix')
let path = require('path')

mix
  .setPublicPath('dist')
  .js('resources/js/tool.js', 'js')
  .sass('resources/sass/tool.scss', 'css')
  .vue({ version: 3 })
  .webpackConfig({
    externals: {
      vue: 'Vue',
    },
    resolve: {
      alias: {
        'laravel-nova': path.join(
          __dirname,
          'vendor/laravel/nova/resources/js/mixins/packages.js'
        ),
      },
    },
    output: {
      uniqueName: 'codenco-dev/nova-grid-system',
    },
  })
