@extends('masters::layouts.master')

@section('content')
@php
    $labels = \Session::get('labels');
@endphp
    <h1>Hello World</h1>

    <p>
        This view is loaded from module: {!! config('masters.name') !!}
    </p>
@endsection
