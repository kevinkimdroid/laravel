@extends('layouts.app')

@section('title', 'New Financial Record')

@section('content')
<h3 class="mb-3">New Financial Record</h3>

<form method="POST" action="{{ route('financial-records.store') }}">
    @include('financial_records._form', ['record' => $record ?? null])
</form>
@endsection


