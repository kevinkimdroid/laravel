@csrf

@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="row">
    <div class="col-md-4 mb-3">
        <label class="form-label">Name</label>
        <input type="text" name="name" class="form-control"
               value="{{ old('name', $record->name ?? '') }}" required>
    </div>
    <div class="col-md-2 mb-3">
        <label class="form-label">Initials</label>
        <input type="text" name="initials" class="form-control"
               value="{{ old('initials', $record->initials ?? '') }}">
    </div>
    <div class="col-md-3 mb-3">
        <label class="form-label">Registration</label>
        <input type="text" name="registration" class="form-control"
               value="{{ old('registration', $record->registration ?? '') }}">
    </div>
    <div class="col-md-3 mb-3">
        <label class="form-label">Aging</label>
        <input type="number" min="0" name="aging" class="form-control"
               value="{{ old('aging', $record->aging ?? 0) }}">
    </div>
</div>

<h5 class="mt-3">Monthly Amounts</h5>
<div class="row">
    @php
        $months = ['jan','feb','mar','apr','may','jun','jul','aug','sep','oct','nov','dec'];
    @endphp

    @foreach ($months as $month)
        <div class="col-md-2 mb-3">
            <label class="form-label text-uppercase">{{ $month }}</label>
            <input type="number" step="0.01" min="0" name="{{ $month }}" class="form-control"
                   value="{{ old($month, $record->$month ?? 0) }}">
        </div>
    @endforeach
</div>

<div class="row">
    <div class="col-md-4 mb-3">
        <label class="form-label">Expected Amount</label>
        <input type="number" step="0.01" min="0" name="expected_amount" class="form-control"
               value="{{ old('expected_amount', $record->expected_amount ?? 0) }}">
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label">Deficit</label>
        <input type="number" step="0.01" name="deficit" class="form-control"
               value="{{ old('deficit', $record->deficit ?? 0) }}">
    </div>
</div>

<button type="submit" class="btn btn-success">
    Save
</button>
<a href="{{ route('financial-records.index') }}" class="btn btn-secondary">
    Cancel
</a>


