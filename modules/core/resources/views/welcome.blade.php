@extends('layouts.guest')

@section('content')
<h1>RikkeiSoft's Intranet</h1>
<p>...</p>
<p>
    <a class="btn btn-primary btn-lg" href="{{ url('auth/connect', ['google']) }}" role="button">{{ trans('core::view.Sign in') }}</a>
</p>

<a class="btn btn-primary btn-md" href="{{ URL::route('core::change-locale', ['locale' => 'vi']) }}" role="button">{{ Config::get('app.locales.vi') }}</a>
<a class="btn btn-primary btn-md" href="{{ URL::route('core::change-locale', ['locale' => 'en']) }}" role="button">{{ Config::get('app.locales.en') }}</a>
<a class="btn btn-primary btn-md" href="{{ URL::route('core::change-locale', ['locale' => 'ko']) }}" role="button">Korean</a>
@endsection