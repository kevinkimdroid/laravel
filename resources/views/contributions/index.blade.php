
@extends('layouts.app')
@section('content')
<h3>Contributions</h3>
<a href="{{ route('contributions.create') }}" class="btn btn-primary mb-3">Post Contribution</a>
<table class="table table-bordered">
<tr><th>Member</th><th>Amount</th><th>Date</th></tr>
@foreach($contributions as $c)
<tr>
<td>{{ $c->member->name }}</td>
<td>{{ number_format($c->amount,2) }}</td>
<td>{{ $c->contribution_date }}</td>
</tr>
@endforeach
</table>
@endsection
