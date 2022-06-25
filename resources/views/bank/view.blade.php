@php
use App\Libraries\Symfinance;
@endphp

@extends('layouts.main')

@section('title', 'Bank Details')

@section('main')
<div class="row">
    <div class="col-12 col-lg-6">
        <img src="{{ $bank->hasPhoto() ? url('/bank/view/'.$bank->id.'/photo') : '/assets/default-image.png' }}" width="100%">
    </div>
    <div class="col-12 col-lg-6">
        <div class="py-2">Code : <strong>{{ $bank->code }}</strong></div>
        <div class="py-2">Name : <strong>{{ $bank->name }}</strong></div>
        <div class="py-2">Address : <strong>{{ $bank->address }}</strong></div>
        <div class="py-2">Deposit Fee : <strong>{{ Symfinance::rupiah($bank->deposit_fee) }}</strong></div>
        <div class="py-2">Withdraw Fee : <strong>{{ Symfinance::rupiah($bank->withdraw_fee) }}</strong></div>
    </div>
</div>
<div class="py-2">
    <div>Transfer Fees :</div>
    <div class="table-responsive">
        <table class="table table-striped caption-top">
            <thead>
                <tr>
                    <th scope="col">Destination Bank</th>
                    <th scope="col">Code</th>
                    <th scope="col">Transfer Fee</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($bank->transferFeesData as $transferFee)
                    <tr>
                        <td>{{ $transferFee->name }}</td>
                        <td>{{ $transferFee->code }}</td>
                        <td>{{ Symfinance::rupiah($transferFee->pivot->amount) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <small class="text-muted">Fees other than banks listed above are {{ Symfinance::rupiah(0) }}</small>
</div>
@endsection
