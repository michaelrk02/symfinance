@extends('layouts.main')

@section('title', 'Accounts List')

@section('main')
<div class="py-2 table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Bank</th>
                <th scope="col">Number</th>
                <th scope="col">Name</th>
                <th scope="col">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($accounts as $i => $account)
                <tr>
                    <th scope="row">{{ $i + 1 }}</th>
                    <td>{{ $account->bankData->name }}</td>
                    <td>{{ $account->number }}</td>
                    <td>{{ $account->name }}</td>
                    <td>
                        <a class="btn btn-primary" href="{{ url('/account/view/'.$account->id) }}"><i class="bi bi-eye me-2"></i> Details</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
<div class="py-2">
    <a class="btn btn-success" href="{{ url('account/open') }}"><i class="bi bi-plus-lg me-2"></i> Open</a>
</div>
@endsection
