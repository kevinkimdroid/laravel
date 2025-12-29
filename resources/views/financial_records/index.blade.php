@extends('layouts.app')

@section('title', 'Financial Records')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3>Financial Records</h3>
    <a href="{{ route('financial-records.create') }}" class="btn btn-primary btn-sm">
        New Record
    </a>
</div>

@if (session('status'))
    <div class="alert alert-success">
        {{ session('status') }}
    </div>
@endif

<div class="card">
    <div class="card-body p-0">
        <table class="table mb-0 table-striped table-sm">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Initials</th>
                    <th>Registration</th>
                    <th>Expected Amount</th>
                    <th>Deficit</th>
                    <th>Aging</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($records as $record)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $record->name }}</td>
                        <td>{{ $record->initials }}</td>
                        <td>{{ $record->registration }}</td>
                        <td>{{ number_format($record->expected_amount, 2) }}</td>
                        <td>{{ number_format($record->deficit, 2) }}</td>
                        <td>{{ $record->aging }}</td>
                        <td class="text-end">
                            <a href="{{ route('financial-records.show', $record) }}" class="btn btn-sm btn-outline-secondary">View</a>
                            <a href="{{ route('financial-records.edit', $record) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                            <form action="{{ route('financial-records.destroy', $record) }}" method="POST" class="d-inline"
                                  onsubmit="return confirm('Are you sure you want to delete this record?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center">No financial records yet</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">
    {{ $records->links() }}
</div>
@endsection


