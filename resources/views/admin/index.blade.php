@extends('layouts.admin')

@section('content')
@if($count > 0) 
<div class="alert alert-warning alert-dismissible fade show" role="alert">
    <strong>You have {{$count}} applicant approval request/s</strong> <a href="/admin/applicants">click here!!!</a>
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
</div>
@endif


@endsection