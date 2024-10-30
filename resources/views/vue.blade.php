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

    <link rel="stylesheet" href="//unpkg.com/element-plus@2.3.12/dist/index.css" />
    <script src="//unpkg.com/vue@3.3.4/dist/vue.global.prod.js"></script>
    <script src="//unpkg.com/vue-router@4.2.4/dist/vue-router.global.prod.js"></script>
    <script src="//unpkg.com/vuex@4.1.0/dist/vuex.global.prod.js"></script>
    <script src="//unpkg.com/element-plus@2.3.12/dist/index.full.min.js"></script>
    <script src="//unpkg.com/nprogress@0.2.0/nprogress.js"></script>
    <script src="//unpkg.com/dayjs@1.11.9/dayjs.min.js"></script>
    <script src="//unpkg.com/xlsx@0.18.5/dist/xlsx.core.min.js"></script> <!-- excel -->
    <script src="//unpkg.com/qrcode.vue@3.3.3/dist/qrcode.vue.browser.min.js"></script> <!-- qrcode -->
    <script src="//unpkg.com/clipboard@2.0.11/dist/clipboard.min.js"></script> <!-- clipboard -->
    <script src="//unpkg.com/@antv/g2plot@2.4.20/dist/g2plot.min.js"></script> <!-- g2plot -->
    <script src="//unpkg.com/@amap/amap-jsapi-loader@1.0.1/dist/index.js"></script> <!-- amap -->

    <link href="//unpkg.com/font-awesome@4.7.0/css/font-awesome.css" rel="stylesheet" />
    <link href="//unpkg.com/nprogress@0.2.0/nprogress.css" rel="stylesheet" /> <!-- 进度条 -->

    <!-- edit -->
    <link href="//unpkg.com/@wangeditor/editor@5.1.23/dist/css/style.css" rel="stylesheet">
    <script src="//unpkg.com/@wangeditor/editor@5.1.23/dist/index.js"></script>
    <!-- edit -->


    <link href="{{ mix('css/base.css','dist') }}" rel="stylesheet" />



</head>

<body>

    <div id="app">
        <app></app>
    </div>


</body>

<script src="{{ mix('js/app.js','dist') }}"></script>