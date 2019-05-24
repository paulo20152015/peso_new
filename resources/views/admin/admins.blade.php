@extends('layouts.admin')

@section('admin-active','active')
@section('admin-active-triangle','triangle-nav')
@section('content')
    <admins-component :archivestatus="'not-archive'"></admins-component>
@endsection