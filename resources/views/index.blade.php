
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
 
</head>

<body>

<div id="app">
    <app></app>
</div>


</body>
 
<script src="{{ mix('js/app.js','dist') }}"></script>