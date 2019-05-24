@extends('layouts.admin')

@section('companies-active','active')
@section('companies-active-triangle','triangle-nav')
@section('content')
    
    <admins-companies :archivecompany="'not-archive'" />
@endsection