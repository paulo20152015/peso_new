@extends('layouts.company')
@section('job-active','active')
@section('job-active-triangle','triangle-nav')
@section('content')
    <company-post-component :archive_stat='"no"'></company-post-component>
@endsection