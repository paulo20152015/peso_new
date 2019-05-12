@extends('layouts.company')
@section('archive-active','active')
@section('archive-active-triangle','triangle-nav')
@section('content')
    <company-post-component :archive_stat='"yes"'></company-post-component>
@endsection