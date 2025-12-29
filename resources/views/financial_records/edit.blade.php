@extends('layouts.app')

@section('title', 'Edit Financial Record')

@section('content')
<h3 class="mb-3">Edit Financial Record</h3>

<form method="POST" action="{{ route('financial-records.update', $record) }}">
    @method('PUT')
    @include('financial_records._form', ['record' => $record])
</form>
@endsection


