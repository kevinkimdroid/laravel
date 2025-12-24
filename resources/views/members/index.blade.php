@extends('layouts.app')

@section('title', 'Members')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3>Members</h3>
    <a href="{{ route('members.create') }}" class="btn btn-primary">
        Add Member
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<table class="table table-bordered table-striped">
    <thead class="table-dark">
        <tr>
            <th>#</th>
            <th>Member No</th>
            <th>Name</th>
            <th>Phone</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @forelse($members as $member)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $member->member_no }}</td>
            <td>{{ $member->name }}</td>
            <td>{{ $member->phone }}</td>
            <td>
                <a href="{{ route('members.statement', $member->id) }}"
                   class="btn btn-sm btn-info">
                    Statement
                </a>

                <a href="{{ route('members.edit', $member->id) }}"
                   class="btn btn-sm btn-warning">
                    Edit
                </a>

                <form action="{{ route('members.destroy', $member->id) }}"
                      method="POST"
                      class="d-inline"
                      onsubmit="return confirm('Are you sure you want to delete this member?');">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-sm btn-danger">
                        Delete
                    </button>
                </form>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="5" class="text-center">
                No members found
            </td>
        </tr>
        @endforelse
    </tbody>
</table>
@endsection

