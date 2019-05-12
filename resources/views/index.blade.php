
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <title>PESO</title>
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <link rel="stylesheet" href="{{asset('fontawesome/css/font-awesome.css')}}">
    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/animation.css') }}" rel="stylesheet">
</head>
<body class="" style='background:white'>
    <div id="app">
        <body-header></body-header>
        <section-component></section-component>
        <footer-component></footer-component>
    </div>
<!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
</body>
</html>

