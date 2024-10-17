@extends('layouts.app')

@section('title', 'Home')

@section('content')
    <h1>Welcome to the Dashboard!</h1>

    @auth
        <p>Hello, {{ Auth::user()->first_name }} {{ Auth::user()->last_name }}!</p>
    @else
        <p>Please log in to access the dashboard.</p>
    @endauth
    
@endsection
