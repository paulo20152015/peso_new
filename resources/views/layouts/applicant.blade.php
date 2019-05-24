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
<body class="hold-transition ">
<div class="wrapper " id='app'>
  
 <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
    <a class="navbar-brand" href="#"><strong class="h3">PESO</strong> | <i class="fa {{auth()->user()->personal_data->gender == 1?'fa-female':'fa-male'}}" aria-hidden="true"></i> {{auth()->user()->username}}</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
        <li class="nav-item @yield('index')">
            <a class="nav-link btn-outline-secondary" href="/applicant">Job Posts</a>
        </li>
        <li class="nav-item ">
            <a class="nav-link btn-outline-secondary" href="#">Companies</a>
        </li>
        <li class="nav-item @yield('account')">
          <a class="nav-link btn-outline-secondary" href="/applicant/myAccount">My Account</a>
        </li>
        
        </ul>
        <form action="/applicant/logout" method="POST">
            @csrf
            <button type="submit" class="btn btn-navbar bg-light"><i
              class="fa fa-sign-out"></i> logout</button>
        </form>
    </div>
    </div>
  </nav>
  <!-- Content Wrapper. Contains page content -->
  <div class="container">
    @if(auth()->user()->is_approved == 0)
        <div class="alert alert-danger" role="alert">
          Account is not yet approved! 
          @if(auth()->user()->is_resume == 1 && auth()->user()->is_specialization == 1)
            <h3>Please wait for admin approval</h3>
          @else
            <h3>you need to do the following for your account to be approved : </h3>
          @endif
          <ul>
          @if(auth()->user()->is_resume == 0)
                <li>upload a resume.</li>  
          @endif
          @if(auth()->user()->is_specialization == 0)
          <li>Add some specializations that matches yours. </li> 
          @endif
          </ul>
          <p class=""><small>note: you can do this at <a href="/applicant/myAccount">My Account</a> section</small></p>
        </div>
    @endif
    
    
    @yield('content')
  </div>
  <!-- /.content-wrapper -->


  <!-- Main Footer -->
  <footer class="fixed-bottom bg-dark mt-5">
    <!-- To the right -->
    <div class="container">
    <div class="float-right d-none d-sm-inline">
      Public Employment Service Office Online Job Application Website.
    </div>
    <!-- Default to the left -->
    <strong>Copyright &copy;</strong> All rights reserved 2019-2020.
    </div>
</footer>
</div>
<!-- ./wrapper -->

<!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
</body>
</html>
