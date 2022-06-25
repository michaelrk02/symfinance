@php
use App\Libraries\Symfinance;
@endphp

@extends('layouts.main')

@section('title', 'Confirm Deposit')

@section('main')
<div class="py-2">
    <div class="alert alert-info">
        Deposit to <strong>{{ $account->bankData->name }} {{ $account->number }}</strong> (fee {{ Symfinance::rupiah($account->bankData->deposit_fee) }})
    </div>
</div>
<form class="py-2" method="post" action="{{ url('/account/deposit/'.$account->id.'/confirm') }}">
    @csrf
    <input type="hidden" name="amount" value="{{ $deposit['amount'] }}">
    <div class="py-2">Amount : {{ Symfinance::rupiah($deposit['amount']) }}</div>
    <div class="py-2">Fee : {{ Symfinance::rupiah($deposit['fee']) }}</div>
    <div class="py-2">Balance Change : {{ Symfinance::rupiah($deposit['balance_change']) }}</div>
    <div class="py-2">Old Balance : {{ Symfinance::rupiah($deposit['old_balance']) }}</div>
    <div class="py-2">New Balance : {{ Symfinance::rupiah($deposit['new_balance']) }}</div>
    <div class="py-2">
        <label class="form-label">Security PIN <span class="text-danger">*</span></label>
        <input type="password" class="form-control" name="pin" placeholder="Enter your PIN to continue">
    </div>
    <div class="py-2">
        <button type="submit" class="btn btn-success"><i class="bi bi-check-lg me-2"></i> Confirm</button>
    </div>
</form>
@endsection
