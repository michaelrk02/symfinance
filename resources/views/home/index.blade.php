@extends('layouts.main')

@section('title', 'Home')

@section('main')

@guest
<div class="py-2">
    <div class="alert alert-warning">
        <h5 class="alert-heading">Logged Out</h5>
        <p class="py-2">You are still logged out. You cannot access your account unless you decide to login. Click the button below to proceed</p>
        <hr>
        <div class="py-2"><a class="btn btn-success" href="{{ url('/user/login') }}"><i class="bi bi-box-arrow-in-right me-2"></i> Login</a></div>
    </div>
</div>
@endguest

@auth
<div class="py-2">
    <div class="alert alert-info">Hello, <strong>{{ Auth::user()->name }}</strong>!</div>
</div>
@endauth

@endsection
