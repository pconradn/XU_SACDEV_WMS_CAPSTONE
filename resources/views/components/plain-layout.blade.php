@props(['title' => null])

@extends('layouts.plain')

@section('content')
    {{ $slot }}
@endsection
