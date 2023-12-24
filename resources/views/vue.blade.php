
<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{$web_name??'-'}}</title>
    <meta name="keywords" content="{{$keyword??'-'}}">
    <meta name="description" content="{{$description??'-'}}" />

    <!-- <script src="https://cdn.jsdelivr.net/npm/vue-i18n@8.24.2/dist/vue-i18n.min.js"></script> 国际化 -->

    <link rel="stylesheet" href="//cdn.bootcdn.net/ajax/libs/element-plus/2.3.12/index.min.css" /> 
    <script src="//cdn.bootcdn.net/ajax/libs/vue/3.3.4/vue.global.min.js"></script>
    <script src="//cdn.bootcdn.net/ajax/libs/vue-router/4.2.4/vue-router.global.min.js"></script>
    <script src="//cdn.bootcdn.net/ajax/libs/vuex/4.1.0/vuex.global.min.js"></script>
    <script src="//cdn.bootcdn.net/ajax/libs/element-plus/2.3.12/index.full.min.js"></script>
    <script src="//cdn.bootcdn.net/ajax/libs/nprogress/0.2.0/nprogress.min.js"></script>
    <script src="//cdn.bootcdn.net/ajax/libs/dayjs/1.11.9/dayjs.min.js"></script>
    <script src="//cdn.bootcdn.net/ajax/libs/xlsx/0.18.5/xlsx.core.min.js"></script> <!-- excel -->
    <script src="//cdn.jsdelivr.net/npm/qrcode.vue@3.4.1/dist/qrcode.vue.browser.min.js"></script> <!-- qrcode -->
    <script src="//cdn.bootcdn.net/ajax/libs/clipboard.js/2.0.11/clipboard.min.js"></script> <!-- clipboard -->
    <script src="//lib.baomitu.com/g2plot/1.2.0-beta.0/g2plot.min.js"></script> <!-- g2plot -->
    <script src="//cdn.jsdelivr.net/npm/@amap/amap-jsapi-loader@1.0.1/dist/index.min.js"></script> <!-- amap -->

    <link href="//lib.baomitu.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" />
    <link href="//cdn.bootcdn.net/ajax/libs/nprogress/0.2.0/nprogress.min.css" rel="stylesheet" /> <!-- 进度条 -->

    <!-- edit -->
    <link href="//cdn.bootcdn.net/ajax/libs/wangeditor5/5.1.23/css/style.min.css" rel="stylesheet">
    <script src="//cdn.bootcdn.net/ajax/libs/wangeditor5/5.1.23/index.min.js"></script>
    <!-- edit -->


    <link href="{{ mix('css/base.css','dist') }}" rel="stylesheet" />

    
 
</head>

<body>

<div id="app">
    <app></app>
</div>


</body>
 
<script src="{{ mix('js/app.js','dist') }}"></script>