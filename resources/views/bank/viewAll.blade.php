@extends('layouts.main')

@section('title', 'Banks List')

@section('main')
<div class="table-responsive">
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
                        <a class="btn btn-primary" href="{{ url('/bank/view/'.$bank->id) }}"><i class="bi bi-eye me-2"></i> Details</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
