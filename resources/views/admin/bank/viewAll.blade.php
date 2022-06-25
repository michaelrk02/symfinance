@extends('layouts.admin')

@section('title', 'Banks List')

@section('main')
<div class="py-2 table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th scope="col">Code</th>
                <th scope="col">Name</th>
                <th scope="col">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($banks as $i => $bank)
                <tr>
                    <th scope="row">{{ $bank->code }}</th>
                    <td>{{ $bank->name }}</td>
                    <td>
                        <a class="btn btn-primary" href="{{ url('/admin/bank/transferFee/'.$bank->id.'/viewAll') }}" target="_blank"><i class="bi bi-coin me-2"></i> Transfer Fees</a>
                        <a class="btn btn-primary" href="{{ url('/admin/bank/edit/'.$bank->id) }}"><i class="bi bi-pencil me-2"></i> Edit</a>
                        <form method="post" class="m-0 d-inline" action="{{ url('admin/bank/remove/'.$bank->id) }}" onclick="return confirm('Are you sure?')">
                            @csrf
                            <button type="submit" class="btn btn-danger"><i class="bi bi-trash me-2"></i> Remove</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
<div class="py-2">
    <a class="btn btn-success" href="{{ url('/admin/bank/add') }}"><i class="bi bi-plus-lg me-2"></i> Add New Bank</a>
</div>
@endsection
