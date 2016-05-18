@extends('layouts.guest')

@section('content')
<h1>RikkeiSoft's Intranet</h1>
<p>...</p>
<p>
    <a class="btn btn-primary btn-lg" href="{{ url('auth/connect', ['google']) }}" role="button">Sign in</a>
</p>
@endsection