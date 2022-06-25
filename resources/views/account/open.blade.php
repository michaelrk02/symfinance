@php
use App\Libraries\Symfinance;
@endphp

@extends('layouts.main')

@section('title', 'Open Bank Account')

@section('main')
<div class="py-2">
    <div class="alert alert-info">
        Open account for <strong>{{ $user->name }}</strong>. Refer to <a target="_blank" href="{{ url('/bank/viewAll') }}">this page</a> for information on bank codes
    </div>
</div>
<form class="py-2" method="post" action="{{ url('/account/open') }}" onsubmit="return confirm('Are you sure?')">
    @csrf
    <div class="py-2">
        <label class="form-label">Bank <span class="text-danger">*</span></label>
        <input type="number" class="form-control" name="bank" placeholder="Enter bank code" value="{{ old('bank') }}">
    </div>
    <div class="py-2">
        <label class="form-label">Name <span class="text-danger">*</span></label>
        <input type="text" class="form-control" name="name" placeholder="Enter account name" value="{{ old('name') }}">
    </div>
    <div class="py-2">
        <label class="form-label">PIN <span class="text-danger">*</span></label>
        <input type="password" class="form-control" name="pin" placeholder="Enter 6-digit security PIN" value="{{ old('pin') }}">
    </div>
    <div class="py-2">
        <label class="form-label">PIN confirmation <span class="text-danger">*</span></label>
        <input type="password" class="form-control" name="pinconf" placeholder="Repeat your security PIN" value="{{ old('pinconf') }}">
    </div>
    <div class="py-2">
        <label class="form-label">Initial balance <span class="text-danger">*</span></label>
        <input type="number" class="form-control" name="balance" placeholder="Enter initial balance (greater than or equal to {{ Symfinance::rupiah(100000) }})" value="{{ old('balance') }}">
    </div>
    <div class="py-2">
        <button type="submit" class="btn btn-success"><i class="bi bi-plus-lg me-2"></i> Open</button>
    </div>
</form>
@endsection
