@extends('layouts.app')

@section('title', 'Members')

@section('content')
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center flex-wrap">
            <div>
                <h3 class="mb-1 fw-bold text-primary">
                    <i class="bi bi-people-fill me-2"></i>Members Management
                </h3>
                <p class="text-muted mb-0">Total Members: <strong>{{ $members->count() }}</strong></p>
            </div>
            <div class="mt-3 mt-md-0">
                <a href="{{ route('whatsapp.index') }}" class="btn btn-success me-2" style="background-color: #25D366; border-color: #25D366;">
                    <i class="bi bi-whatsapp me-1"></i>WhatsApp Reminders
                </a>
                <a href="{{ route('members.import.form') }}" class="btn btn-outline-success me-2">
                    <i class="bi bi-upload me-1"></i>Import CSV
                </a>
                <a href="{{ route('members.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-1"></i>Add Member
                </a>
            </div>
        </div>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if(session('warning'))
    <div class="alert alert-warning alert-dismissible fade show shadow-sm" role="alert">
        <i class="bi bi-exclamation-triangle me-2"></i>{{ session('warning') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if(session('import_errors'))
    <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
        <i class="bi bi-x-circle me-2"></i><strong>Import Errors:</strong>
        <ul class="mb-0 mt-2">
            @foreach(session('import_errors') as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                    <tr>
                        <th class="text-white">#</th>
                        <th class="text-white">Member No</th>
                        <th class="text-white">Name</th>
                        <th class="text-white">Phone</th>
                        <th class="text-white">Registration</th>
                        <th class="text-white">Date</th>
                        <th class="text-white">Status</th>
                        <th class="text-white text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($members as $member)
                    <tr>
                        <td class="fw-semibold">{{ $loop->iteration }}</td>
                        <td><span class="badge bg-primary">{{ $member->member_no }}</span></td>
                        <td class="fw-semibold">{{ $member->name }}</td>
                        <td>
                            @if($member->phone)
                                <i class="bi bi-telephone me-1"></i>{{ $member->phone }}
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            @if($member->isRegistered ?? false)
                                <span class="badge bg-success">
                                    <i class="bi bi-check-circle me-1"></i>Registered
                                </span>
                                <br>
                                <small class="text-success">KES {{ number_format($member->registrationFeePaid ?? 0, 2) }} (in bank)</small>
                            @else
                                <span class="badge bg-warning text-dark">
                                    <i class="bi bi-exclamation-triangle me-1"></i>Not Registered
                                </span>
                                <br>
                                <small class="text-muted">KES {{ number_format($member->registrationFeePaid ?? 0, 2) }} / 1,000.00</small>
                            @endif
                        </td>
                        <td>
                            <i class="bi bi-calendar3 me-1 text-muted"></i>
                            <span class="text-muted">{{ $member->created_at->format('M d, Y') }}</span>
                        </td>
                        <td>
                            @if($member->status === 'ACTIVE')
                                <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>Active</span>
                            @else
                                <span class="badge bg-secondary"><i class="bi bi-x-circle me-1"></i>Inactive</span>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('members.show', $member->id) }}"
                                   class="btn btn-sm btn-primary" title="View Details">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('members.statement', $member->id) }}"
                                   class="btn btn-sm btn-info" title="View Statement">
                                    <i class="bi bi-file-text"></i>
                                </a>
                                <a href="{{ route('members.edit', $member->id) }}"
                                   class="btn btn-sm btn-warning" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('members.destroy', $member->id) }}"
                                      method="POST"
                                      class="d-inline"
                                      onsubmit="return confirm('Are you sure you want to delete this member?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger" title="Delete">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <i class="bi bi-inbox display-4 text-muted d-block mb-3"></i>
                            <p class="text-muted">No members found. Start by adding a new member.</p>
                            <a href="{{ route('members.create') }}" class="btn btn-primary">
                                <i class="bi bi-plus-circle me-1"></i>Add First Member
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

