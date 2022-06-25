@extends('layouts.skeleton')

@section('navbar')
<li class="nav-item"><a class="nav-link {{ Route::currentRouteName() === 'home' ? 'active' : '' }}" href="{{ route('home') }}">Home</a></li>
<li class="nav-item"><a class="nav-link {{ Route::currentRouteName() === 'banks' ? 'active' : '' }}" href="{{ route('banks') }}">Banks</a></li>
@auth
    <li class="nav-item"><a class="nav-link {{ Route::currentRouteName() === 'accounts' ? 'active' : '' }}" href="{{ route('accounts') }}">Accounts</a></li>
    <li class="nav-item"><a class="nav-link {{ Route::currentRouteName() === 'profile' ? 'active' : '' }}" href="{{ route('profile') }}">Profile</a></li>
@endauth
@endsection

