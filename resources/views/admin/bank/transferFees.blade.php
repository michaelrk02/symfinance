@php
use App\Libraries\Symfinance;
@endphp

@extends('layouts.admin')

@section('title', 'Transfer Fees')

@section('main')
<div class="py-2">Bank : <strong>{{ $bank->name }}</strong></div>
<div class="py-2">
    <div class="table-responsive">
        <table class="table table-striped caption-top">
            <thead>
                <tr>
                    <th scope="col">Destination Bank</th>
                    <th scope="col">Code</th>
                    <th scope="col">Transfer Fee</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($bank->transferFeesData as $transferFee)
                    <tr>
                        <td>{{ $transferFee->name }}</td>
                        <td>{{ $transferFee->code }}</td>
                        <td>{{ Symfinance::rupiah($transferFee->pivot->amount) }}</td>
                        <td>
                            <form class="m-0 d-inline" method="post" action="{{ url('/admin/bank/transferFee/'.$bank->id.'/remove/'.$transferFee->id) }}" onsubmit="return confirm('Are you sure?')">
                                @csrf
                                <button type="submit" class="btn btn-danger"><i class="bi bi-trash me-2"></i> Remove</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <small class="text-muted">Fees other than banks listed above are {{ Symfinance::rupiah(0) }}</small>
</div>
<div class="py-2">
    <div class="card">
        <div class="card-header">Add Transfer Fee</div>
        <div class="card-body">
            <form method="post" action="{{ url('/admin/bank/transferFee/'.$bank->id.'/add') }}" onsubmit="return confirm('Are you sure?')">
                @csrf
                <div class="py-2">
                    <label class="form-label">Destination Bank <span class="text-danger">*</span></label>
                    <input type="number" class="form-control" name="dstbank" placeholder="Enter bank code" value="{{ old('dstbank') }}">
                </div>
                <div class="py-2">
                    <label class="form-label">Fee <span class="text-danger">*</span></label>
                    <input type="number" class="form-control" name="amount" placeholder="Enter amount (IDR)" value="{{ old('amount') }}">
                </div>
                <div class="py-2">
                    <button type="submit" class="btn btn-success"><i class="bi bi-send me-2"></i> Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
