
@extends('layouts.app')
@section('content')
<h3>Post Contribution</h3>
<form method="POST" action="{{ route('contributions.store') }}">
@csrf
<select name="member_id" class="form-control mb-2">
@foreach($members as $member)
<option value="{{ $member->id }}">{{ $member->name }}</option>
@endforeach
</select>
<input class="form-control mb-2" name="amount" placeholder="Amount">
<input type="date" class="form-control mb-2" name="contribution_date">
<button class="btn btn-success">Post</button>
</form>
@endsection
