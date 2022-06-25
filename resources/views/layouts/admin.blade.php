@extends('layouts.skeleton')

@section('banner')
<div class="alert alert-warning">SUPERUSER MODE</div>
@endsection

@section('navbar')
@auth('admin')
    <li class="nav-item"><a class="nav-link" href="{{ url('admin/bank/viewAll') }}">Banks</a></li>
    <li class="nav-item"><a class="nav-link" href="{{ url('admin/logout') }}">Logout</a></li>
@endauth
@endsection

