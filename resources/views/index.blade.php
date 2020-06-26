
<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>青梧商城</title>
    <meta name="keywords" content="青梧商城">
    <meta name="description" content="青梧商城" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/ant-design-vue@1.6.2/dist/antd.min.css">
    <script src="https://cdn.bootcdn.net/ajax/libs/vue/2.6.11/vue.min.js"></script>
    <script src="https://cdn.bootcdn.net/ajax/libs/vue-router/3.2.0/vue-router.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/moment@2.27.0/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/ant-design-vue@1.6.2/dist/antd.min.js"></script>
 
</head>

<body>

<div id="app">
    <app></app>
</div>


</body>
 
<script src="{{ mix('js/app.js','dist') }}"></script>