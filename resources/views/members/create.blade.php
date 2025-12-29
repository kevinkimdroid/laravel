
@extends('layouts.app')

@section('title', 'Add Member')

@section('content')
<h3>Add Member</h3>

@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="POST" action="{{ route('members.store') }}">
    @csrf

    <div class="mb-2">
        <input class="form-control" name="member_no" placeholder="Member No" value="{{ old('member_no') }}" required>
    </div>

    <div class="mb-2">
        <input class="form-control" name="name" placeholder="Full Name" value="{{ old('name') }}" required>
    </div>

    <div class="mb-2">
        <input class="form-control" name="initials" placeholder="Initials" value="{{ old('initials') }}" required>
    </div>

    <div class="mb-2">
        <input class="form-control" name="registration_amount_paid" placeholder="Registration Amount Paid" value="{{ old('registration_amount_paid') }}" required>
    </div>

    <div class="mb-2">
        <input class="form-control" name="paid_to_date" placeholder="Paid To Date" value="{{ old('paid_to_date') }}" required>
    </div>

    <div class="mb-2">
        <input class="form-control" name="phone" placeholder="Phone" value="{{ old('phone') }}">
    </div>

    <button class="btn btn-success">Save</button>
</form>
@endsection
