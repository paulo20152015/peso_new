@extends('layouts.applicant')
@section('index','bot-triangle-nav')
@section('content')
    @can('approved_applicants', App\Applicant_account::class)
        <jobpost-component></jobpost-component>
    @endcan
@endsection