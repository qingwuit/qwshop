const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

require('laravel-mix-polyfill');
mix.js('resources/js/app.js', 'public/dist/js')
   .sass('resources/sass/app.scss', 'public/dist/css')
   .setPublicPath('public/dist')
   .setResourceRoot('/dist/')
   .polyfill({
      enabled: true,
      useBuiltIns: "usage",
      targets: {"firefox": "50", "ie": 11},
   })
   .webpackConfig({
        output: {
            publicPath: '/dist/',
            filename: '[name].js',
            chunkFilename : '[name].js?id=[chunkhash:20]'
        },
        resolve: {
            alias: {
                '@': path.resolve('resources/js/')
            }
        }
    })
    .version();


