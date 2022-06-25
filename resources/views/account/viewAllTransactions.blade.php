@php
use App\Libraries\Symfinance;
@endphp

@extends('layouts.main')

@section('title', 'Transaction History')

@section('main')
<div class="py-2">
    <div class="alert alert-info">
        Account : <strong>{{ $account->bankData->name }} {{ $account->number }}</strong>
    </div>
</div>
<div class="py-2 table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th scope="col">Timestamp</th>
                <th scope="col">Type</th>
                <th scope="col">Amount</th>
                <th scope="col">Balance</th>
                <th scope="col">Description</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($transactions as $i => $transaction)
                <tr>
                    <td>{{ $transaction->timestamp }}</td>
                    <td>{{ strtoupper($transaction->type) }}</td>
                    <td>{{ Symfinance::rupiah($transaction->amount) }}</td>
                    <td><span class="{{ $transaction->type === 'credit' ? 'text-success' : 'text-danger' }}">{{ Symfinance::rupiah($transaction->balance) }}</span></td>
                    <td class="font-monospace">{{ strtoupper($transaction->description) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
