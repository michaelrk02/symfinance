@extends('layouts.admin')

@section('title', 'Admin Login')

@section('main')
<form method="post" action="{{ url('admin/login') }}">
    @csrf
    <div class="py-2">
        <label class="form-label">E-mail</label>
        <input type="text" class="form-control" name="email" placeholder="Enter your e-mail" value="{{ old('email') }}">
    </div>
    <div class="py-2">
        <label class="form-label">Password</label>
        <input type="password" class="form-control" name="password" placeholder="Enter your password">
    </div>
    <div class="py-2">
        <button type="submit" class="btn btn-success"><i class="bi bi-box-arrow-in-right me-2"></i> Login</button>
    </div>
</form>
@endsection
