const mix = require('laravel-mix');
const path = require('path')
/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */
require('laravel-mix-polyfill');
mix.js('resources/js/app.js', 'public/dist/js').vue({ version: 3 })
    .sass('resources/js/plugins/css/base.scss','public/css')
    // .postCss('resources/css/app.css', 'public/dist/css', [
    //     //
    // ])
    .setPublicPath('public/dist')
    .setResourceRoot('/dist/')
    .polyfill({
        enabled: true,
        useBuiltIns: "usage",
        targets: {"firefox": "50", "ie": 11},
    })
    .webpackConfig({
        externals: {
            'vue': 'Vue',//这些是你不需要webpakc帮你打包的库
            'vue-router': 'VueRouter',
            'vuex': 'Vuex',
            'element-plus': 'ElementPlus',
            'dayjs':'dayjs',
            'qrcode.vue':'QrcodeVue',
            'clipboardjs':'ClipboardJS',
            'g2plot':'G2Plot',
            'amap-loader':'AMapLoader',
            // 'vue-i18n':'VueI18n',
        },
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
