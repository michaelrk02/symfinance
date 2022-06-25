@php
use App\Libraries\Symfinance;
@endphp

@extends('layouts.main')

@section('title', 'Deposit To Account')

@section('main')
<div class="py-2">
    <div class="alert alert-info">
        Deposit to <strong>{{ $account->bankData->name }} {{ $account->number }}</strong> (fee {{ Symfinance::rupiah($account->bankData->deposit_fee) }})
    </div>
</div>
<div class="py-2">Current balance is <strong>{{ Symfinance::rupiah($account->balance) }}</strong></div>
<form class="py-2" method="post" action="{{ url('/account/deposit/'.$account->id) }}">
    @csrf
    <div class="py-2">
        <label class="form-label">Amount <span class="text-danger">*</span></label>
        <input type="number" class="form-control" name="amount" placeholder="Enter deposit amount" value="{{ old('amount') }}">
    </div>
    <div class="py-2">
        <button type="submit" class="btn btn-success"><i class="bi bi-download me-2"></i> Deposit</button>
    </div>
</form>
@endsection
