@extends('layouts.admin')

@section('archive-active','active')
@section('archive-active-triangle','triangle-nav')
@section('content')
    @can('authorizeAdmin', App\Admin::class)
    <admins-component :archivestatus="'archive'"></admins-component>
    @endcan
    <applicants-component :archiveapplicant="'1'"></applicants-component>
    <admins-companies :archivecompany="'archive'" />
@endsection