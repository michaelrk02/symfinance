@php
use App\Libraries\Symfinance;
@endphp

@extends('layouts.main')

@section('title', 'Account Details')

@section('main')
<div class="py-2">Bank : <strong>{{ $account->bankData->name }}</strong></div>
<div class="py-2">Number : <strong>{{ $account->number }}</strong></div>
<div class="py-2">Name : <strong>{{ $account->name }}</strong></div>
<div class="py-2">
    <div class="alert alert-info">
        Your balance is <strong>{{ Symfinance::rupiah($account->balance) }}</strong>
    </div>
</div>
<div class="py-2">
    <a class="btn btn-warning me-2" href="{{ url('account/transaction/'.$account->id.'/viewAll') }}"><i class="bi bi-clock-history me-2"></i> Transactions</a>
    <a class="btn btn-primary me-2" href="{{ url('account/deposit/'.$account->id) }}"><i class="bi bi-download me-2"></i> Deposit</a>
    <a class="btn btn-primary me-2" href="{{ url('account/withdraw/'.$account->id) }}"><i class="bi bi-upload me-2"></i> Withdraw</a>
    <a class="btn btn-primary me-2" href="{{ url('account/transfer/'.$account->id) }}"><i class="bi bi-send me-2"></i> Transfer</a>
    <form class="my-0 ms-0 me-2 d-inline" method="post" action="{{ url('account/close/'.$account->id) }}" onsubmit="return confirm('Are you sure?')">
        @csrf
        <button type="submit" class="btn btn-danger"><i class="bi bi-trash me-2"></i> Close</button>
    </form>
</div>
@endsection
