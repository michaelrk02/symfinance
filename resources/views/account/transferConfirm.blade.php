@php
use App\Libraries\Symfinance;
@endphp

@extends('layouts.main')

@section('title', 'Confirm Transfer')

@section('main')
<div class="py-2">
    <div class="alert alert-info">
        Transfer from <strong>{{ $account->bankData->name }} {{ $account->number }}</strong>. See <a target="_blank" href="{{ url('/bank/view/'.$account->bank) }}">this page</a> for informations regarding on transfer fees and <a target="_blank" href="{{ url('bank/viewAll') }}">here</a> for bank codes
    </div>
</div>
<form class="py-2" method="post" action="{{ url('/account/transfer/'.$account->id.'/confirm') }}">
    @csrf
    <input type="hidden" name="dst_bank" value="{{ $transfer['dst_bank_code'] }}">
    <input type="hidden" name="dst_account" value="{{ $transfer['dst_account_number'] }}">
    <input type="hidden" name="amount" value="{{ $transfer['amount'] }}">
    <div class="py-2">Destination Bank Code : {{ $transfer['dst_bank_code'] }}</div>
    <div class="py-2">Destination Bank Name : {{ $transfer['dst_bank_name'] }}</div>
    <div class="py-2">Destination Account Number : {{ $transfer['dst_account_number'] }}</div>
    <div class="py-2">Destination Account Name : {{ $transfer['dst_account_name'] }}</div>
    <div class="py-2">Amount : {{ Symfinance::rupiah($transfer['amount']) }}</div>
    <div class="py-2">Fee : {{ Symfinance::rupiah($transfer['fee']) }}</div>
    <div class="py-2">Balance Change : {{ Symfinance::rupiah($transfer['src_balance_change']) }}</div>
    <div class="py-2">Old Balance : {{ Symfinance::rupiah($transfer['src_old_balance']) }}</div>
    <div class="py-2">New Balance : {{ Symfinance::rupiah($transfer['src_new_balance']) }}</div>
    <div class="py-2">
        <label class="form-label">Security PIN <span class="text-danger">*</span></label>
        <input type="password" class="form-control" name="pin" placeholder="Enter your PIN to continue">
    </div>
    <div class="py-2">
        <button type="submit" class="btn btn-success"><i class="bi bi-check-lg me-2"></i> Confirm</button>
    </div>
</form>
@endsection
