@extends('layouts.default')

@section('title')
404 page -
@endsection

@section('content')
@if(isset($message) && $message)
<h4>{{ $message }}</h4>
@endif
@endsection
