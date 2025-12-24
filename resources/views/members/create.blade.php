
@extends('layouts.app')
@section('content')
<h3>Add Member</h3>
<form method="POST" action="{{ route('members.store') }}">
@csrf
<input class="form-control mb-2" name="member_no" placeholder="Member No">
<input class="form-control mb-2" name="name" placeholder="Name">
<input class="form-control mb-2" name="phone" placeholder="Phone">
<button class="btn btn-success">Save</button>
</form>
@endsection
