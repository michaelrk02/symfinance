@extends('layouts.admin')

@if ($mode === 'create')
    @section('title', 'Create New Bank')
@else
    @section('title', 'Update Existing Bank')
@endif

@section('main')
<form id="remove_photo" class="m-0 d-inline" method="post" action="{{ url('/admin/bank/edit/'.$bank->id.'/removephoto') }}" onsubmit="return confirm('Are you sure?')">
@csrf
</form>

<form method="post" action="{{ $action }}" onsubmit="return confirm('Are you sure?')" enctype="multipart/form-data">
    @csrf
    <div class="py-2">
        <label class="form-label">Code <span class="text-danger">*</span></label>
        <input type="number" class="form-control" name="code" placeholder="Enter bank code" value="{{ $bank->code }}">
    </div>
    <div class="py-2">
        <label class="form-label">Name <span class="text-danger">*</span></label>
        <input type="text" class="form-control" name="name" placeholder="Enter bank name" value="{{ $bank->name }}">
    </div>
    <div class="py-2">
        <label class="form-label">Address <span class="text-danger">*</span></label>
        <textarea class="form-control" name="address" rows="5" placeholder="Enter bank address">{{ $bank->address }}</textarea>
    </div>
    <div class="py-2">
        <label class="form-label">Deposit Fee <span class="text-danger">*</span></label>
        <input type="number" class="form-control" name="deposit_fee" placeholder="Enter deposit fee (IDR)" value="{{ $bank->deposit_fee }}">
    </div>
    <div class="py-2">
        <label class="form-label">Withdraw Fee <span class="text-danger">*</span></label>
        <input type="number" class="form-control" name="withdraw_fee" placeholder="Enter withdraw fee (IDR)" value="{{ $bank->withdraw_fee }}">
    </div>
    <div class="py-2">
        <label class="form-label">Photo</label>
        <input type="file" class="form-control" name="photo">
        @if ($bank->hasPhoto())
            <div class="form-text">Photo already axists. <a target="_blank" href="{{ url('/bank/view/'.$bank->id.'/photo') }}">view</a></div>
            <button form="remove_photo" type="submit" class="btn btn-danger"><i class="bi bi-trash me-2"></i> Remove Photo</button>
        @endif
    </div>
    <div class="py-2">
        <button type="submit" class="btn btn-success"><i class="bi bi-send me-2"></i> Submit</button>
    </div>
</form>
@endsection

