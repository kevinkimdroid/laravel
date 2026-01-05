@extends('layouts.app')

@section('title', 'Edit Calendar Activity')

@section('content')
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h3 class="mb-1 fw-bold text-primary">
                    <i class="bi bi-calendar-check me-2"></i>Edit Calendar Activity
                </h3>
                <p class="text-muted mb-0">Update activity details</p>
            </div>
            <a href="{{ route('calendar-activities.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i>Back to Calendar
            </a>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <form method="POST" action="{{ route('calendar-activities.update', $calendarActivity->id) }}">
            @csrf
            @method('PUT')

            <div class="row g-3">
                <div class="col-md-6">
                    <label for="title" class="form-label fw-semibold">Activity Title <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', $calendarActivity->title) }}" required>
                    @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="activity_date" class="form-label fw-semibold">Activity Date <span class="text-danger">*</span></label>
                    <input type="date" class="form-control @error('activity_date') is-invalid @enderror" id="activity_date" name="activity_date" value="{{ old('activity_date', $calendarActivity->activity_date->format('Y-m-d')) }}" required>
                    @error('activity_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="start_time" class="form-label fw-semibold">Start Time</label>
                    <input type="time" class="form-control @error('start_time') is-invalid @enderror" id="start_time" name="start_time" value="{{ old('start_time', $calendarActivity->start_time ? \Carbon\Carbon::parse($calendarActivity->start_time)->format('H:i') : '') }}">
                    @error('start_time')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="end_time" class="form-label fw-semibold">End Time</label>
                    <input type="time" class="form-control @error('end_time') is-invalid @enderror" id="end_time" name="end_time" value="{{ old('end_time', $calendarActivity->end_time ? \Carbon\Carbon::parse($calendarActivity->end_time)->format('H:i') : '') }}">
                    @error('end_time')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="venue" class="form-label fw-semibold">Venue</label>
                    <input type="text" class="form-control @error('venue') is-invalid @enderror" id="venue" name="venue" value="{{ old('venue', $calendarActivity->venue) }}" placeholder="e.g., Club House, Field">
                    @error('venue')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="type" class="form-label fw-semibold">Activity Type <span class="text-danger">*</span></label>
                    <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required>
                        <option value="general" {{ old('type', $calendarActivity->type) == 'general' ? 'selected' : '' }}>General</option>
                        <option value="meeting" {{ old('type', $calendarActivity->type) == 'meeting' ? 'selected' : '' }}>Meeting</option>
                        <option value="event" {{ old('type', $calendarActivity->type) == 'event' ? 'selected' : '' }}>Event</option>
                        <option value="game" {{ old('type', $calendarActivity->type) == 'game' ? 'selected' : '' }}>Game</option>
                        <option value="training" {{ old('type', $calendarActivity->type) == 'training' ? 'selected' : '' }}>Training</option>
                        <option value="other" {{ old('type', $calendarActivity->type) == 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                    @error('type')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="status" class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                    <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                        <option value="scheduled" {{ old('status', $calendarActivity->status) == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                        <option value="completed" {{ old('status', $calendarActivity->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="cancelled" {{ old('status', $calendarActivity->status) == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                    @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12">
                    <label for="description" class="form-label fw-semibold">Description</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3" placeholder="Activity description...">{{ old('description', $calendarActivity->description) }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12">
                    <label for="notes" class="form-label fw-semibold">Notes</label>
                    <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes" rows="2" placeholder="Additional notes...">{{ old('notes', $calendarActivity->notes) }}</textarea>
                    @error('notes')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-circle me-1"></i>Update Activity
                </button>
                <a href="{{ route('calendar-activities.index') }}" class="btn btn-secondary">
                    <i class="bi bi-x-circle me-1"></i>Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
