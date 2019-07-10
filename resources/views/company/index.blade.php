@extends('layouts.company')
@section('content')
    @foreach ($pendings as $item)
        <div class="alert alert-warning alert-dismissible fade show " role="alert">
           <a class="text-light" href="/company/job_post/{{$item->id}}"><strong>{{$item->title}}</strong> has pending application</a> 
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endforeach
@endsection