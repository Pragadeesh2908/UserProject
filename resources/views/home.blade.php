@extends('layouts.app')

@section('title', 'Home')

@section('content')
    <h1>Welcome to the Dashboard!</h1>

    @auth
        <p>Hello, {{ Auth::user()->first_name }} {{ Auth::user()->last_name }}!</p>
        <!-- <form method="POST" action="{{ route('logout') }}" class="d-inline">
            @csrf
            <button type="submit" class="logout-btn nav-link btn btn-link">Logout</button>
        </form> -->
        
    @else
        <p>Please log in to access the dashboard.</p>
    @endauth
    
@endsection
