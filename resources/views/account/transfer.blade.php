@php
use App\Libraries\Symfinance;
@endphp

@extends('layouts.main')

@section('title', 'Transfer')

@section('main')
<div class="py-2">
    <div class="alert alert-info">
        Transfer from <strong>{{ $account->bankData->name }} {{ $account->number }}</strong>. See <a target="_blank" href="{{ url('/bank/view/'.$account->bank) }}">this page</a> for informations regarding on transfer fees and <a target="_blank" href="{{ url('bank/viewAll') }}">here</a> for bank codes
    </div>
</div>
<div class="py-2">Current balance is <strong>{{ Symfinance::rupiah($account->balance) }}</strong></div>
<form class="py-2" method="post" action="{{ url('/account/transfer/'.$account->id.'') }}">
    @csrf
    <div class="py-2">
        <label class="form-label">Destination Bank <span class="text-danger">*</span></label>
        <input type="number" class="form-control" name="dst_bank" placeholder="Enter destination bank code" value="{{ old('dst_bank') }}">
    </div>
    <div class="py-2">
        <label class="form-label">Destination Account <span class="text-danger">*</span></label>
        <input type="number" class="form-control" name="dst_account" placeholder="Enter destination bank account" value="{{ old('dst_account') }}">
    </div>
    <div class="py-2">
        <label class="form-label">Amount <span class="text-danger">*</span></label>
        <input type="number" class="form-control" name="amount" placeholder="Enter transfer amount" value="{{ old('amount') }}">
    </div>
    <div class="py-2">
        <button type="submit" class="btn btn-success"><i class="bi bi-send me-2"></i> Transfer</button>
    </div>
</form>
@endsection
