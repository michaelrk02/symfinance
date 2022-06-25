@php
use App\Libraries\Symfinance;
@endphp

@extends('layouts.main')

@section('title', 'Withdraw From Account')

@section('main')
<div class="py-2">
    <div class="alert alert-info">
        Withdraw from <strong>{{ $account->bankData->name }} {{ $account->number }}</strong> (fee {{ Symfinance::rupiah($account->bankData->withdraw_fee) }})
    </div>
</div>
<div class="py-2">Current balance is <strong>{{ Symfinance::rupiah($account->balance) }}</strong></div>
<form class="py-2" method="post" action="{{ url('/account/withdraw/'.$account->id) }}">
    @csrf
    <div class="py-2">
        <label class="form-label">Amount <span class="text-danger">*</span></label>
        <input type="number" class="form-control" name="amount" placeholder="Enter withdraw amount" value="{{ old('amount') }}">
    </div>
    <div class="py-2">
        <button type="submit" class="btn btn-success"><i class="bi bi-upload me-2"></i> Withdraw</button>
    </div>
</form>
@endsection
