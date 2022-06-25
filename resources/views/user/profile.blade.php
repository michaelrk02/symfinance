@extends('layouts.main')

@section('title', 'Profile')

@section('main')
<div class="py-2">E-mail : <strong>{{ $user->email }}</strong></div>
<div class="py-2">Name : <strong>{{ $user->name }}</strong></div>
<div class="py-2">
    <a class="btn btn-danger" href="{{ url('/user/logout') }}"><i class="bi bi-box-arrow-right me-2"></i> Logout</a>
</div>
@endsection
