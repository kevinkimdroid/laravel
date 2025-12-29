@extends('layouts.app')

@section('title', 'Edit Member')

@section('content')
<h3>Edit Member</h3>

@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('members.update', $member->id) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="mb-3">
        <label class="form-label">Member No</label>
        <input type="text"
               name="member_no"
               class="form-control"
               value="{{ old('member_no', $member->member_no) }}"
               required>
    </div>

    <div class="mb-3">
        <label class="form-label">Name</label>
        <input type="text"
               name="name"
               class="form-control"
               value="{{ old('name', $member->name) }}"
               required>
    </div>

    <div class="mb-3">
        <label class="form-label">Initials</label>
        <input type="text"
               name="initials"
               class="form-control"
               value="{{ old('initials', $member->initials) }}"
               required>
    </div>

    <div class="mb-3">
        <label class="form-label">Registration Amount Paid</label>
        <input type="text"
               name="registration_amount_paid"
               class="form-control"
               value="{{ old('registration_amount_paid', $member->registration_amount_paid) }}"
               required>
    </div>

    <div class="mb-3">
        <label class="form-label">Registration Fee</label>
        <input type="number"
               step="0.01"
               name="registration_fee"
               class="form-control"
               value="{{ old('registration_fee', $member->registration_fee ?? 1000) }}"
               required>
    </div>

    <div class="mb-3">
        <label class="form-label">Paid To Date</label>
        <input type="text"
               name="paid_to_date"
               class="form-control"
               value="{{ old('paid_to_date', $member->paid_to_date) }}"
               required>
    </div>

    <div class="mb-3">
        <label class="form-label">Phone</label>
        <input type="text"
               name="phone"
               class="form-control"
               value="{{ old('phone', $member->phone) }}">
    </div>

    <div class="mb-3">
        <label class="form-label">Status</label>
        <select name="status" class="form-control">
            <option value="ACTIVE" {{ $member->status === 'ACTIVE' ? 'selected' : '' }}>
                ACTIVE
            </option>
            <option value="INACTIVE" {{ $member->status === 'INACTIVE' ? 'selected' : '' }}>
                INACTIVE
            </option>
        </select>
    </div>

    <button type="submit" class="btn btn-primary">
        Update Member
    </button>

    <a href="{{ route('members.index') }}" class="btn btn-secondary">
        Cancel
    </a>
</form>
@endsection
