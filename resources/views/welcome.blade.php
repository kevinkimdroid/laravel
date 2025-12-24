@extends('layouts.app')

@section('title', 'Welcome')

@section('content')
<div class="text-center mt-5">
    <h1>Welcome to the Society Contribution System</h1>
    <p class="lead">Manage your members and contributions easily.</p>
    <a href="{{ route('members.index') }}" class="btn btn-primary me-2">View Members</a>
    <a href="{{ route('contributions.index') }}" class="btn btn-success">View Contributions</a>
</div>
@endsection
