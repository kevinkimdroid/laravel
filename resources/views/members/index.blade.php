@extends('layouts.app')

@section('title', 'Members')

@section('content')
<style>
    .members-toolbar .card-body {
        padding: 1.25rem;
    }
    .members-table th,
    .members-table td {
        padding: 0.55rem 0.75rem;
        vertical-align: middle;
    }
    .members-table th {
        font-weight: 600;
    }
    .members-badge {
        font-size: 0.75rem;
        padding: 0.2rem 0.45rem;
    }
</style>
<div class="card border-0 shadow-sm mb-3 position-relative overflow-hidden members-toolbar" style="background: #FFFFFF; border-bottom: 3px solid #FFD700; border-radius: 16px;">
    <div class="position-absolute" style="top: -30px; right: -30px; width: 120px; height: 120px; background: rgba(255, 215, 0, 0.08); border-radius: 50%;"></div>
    <div class="card-body position-relative" style="z-index: 1;">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <h3 class="mb-1 fw-bold" style="color: #000000;">
                    <i class="bi bi-people-fill me-2" style="color: #FFD700;"></i>Members
                </h3>
                <p class="text-muted mb-0">Total: <strong style="color: #FFD700;">{{ $members->count() }}</strong></p>
            </div>
            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('members.create') }}" class="btn btn-sm" style="background: #FFD700; color: #000000; border-radius: 8px;">
                    <i class="bi bi-plus-circle me-1"></i>Add Member
                </a>
                <div class="dropdown">
                    <button class="btn btn-sm btn-outline-dark dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false" style="border-color: #FFD700; color: #000000;">
                        More actions
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                        <li>
                            <a class="dropdown-item" href="{{ route('whatsapp.index') }}">
                                <i class="bi bi-whatsapp me-2"></i>WhatsApp Reminders
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('members.import.form') }}">
                                <i class="bi bi-upload me-2"></i>Import CSV
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('members.update-phone') }}">
                                <i class="bi bi-telephone-fill me-2"></i>Update Phones
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Search Bar -->
<div class="card border-0 shadow-sm mb-3" style="border-radius: 16px; border-left: 4px solid #FFD700;">
    <div class="card-body p-3">
        <form method="GET" action="{{ route('members.index') }}" class="row g-3 align-items-end">
            <div class="col-md-8">
                <label for="search" class="form-label fw-semibold mb-1">
                    <i class="bi bi-search me-2" style="color: #FFD700;"></i>Search Members
                </label>
                <div class="input-group">
                    <span class="input-group-text" style="background: #f8f9fa; border-color: #FFD700;">
                        <i class="bi bi-search" style="color: #FFD700;"></i>
                    </span>
                    <input type="text" 
                           class="form-control" 
                           id="search" 
                           name="search" 
                           value="{{ request('search') }}" 
                           placeholder="Search by name, member number, phone, or initials..."
                           style="border-color: #FFD700;">
                    @if(request('search'))
                        <a href="{{ route('members.index') }}" class="btn btn-outline-secondary" title="Clear search" style="border-color: #FFD700;">
                            <i class="bi bi-x-lg"></i>
                        </a>
                    @endif
                </div>
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn w-100 btn-sm" style="background: #FFD700; color: #000000; border-radius: 8px;">
                    <i class="bi bi-search me-1"></i>Search
                </button>
            </div>
        </form>
        @if(request('search'))
            <div class="mt-2">
                <small class="text-muted">
                    Showing results for: <strong>"{{ request('search') }}"</strong>
                    <a href="{{ route('members.index') }}" class="text-decoration-none ms-2" style="color: #FFD700;">
                        <i class="bi bi-x-circle me-1"></i>Clear
                    </a>
                </small>
            </div>
        @endif
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show shadow-sm border-0" role="alert" style="border-radius: 12px;">
        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if(session('warning'))
    <div class="alert alert-warning alert-dismissible fade show shadow-sm border-0" role="alert" style="border-radius: 12px;">
        <i class="bi bi-exclamation-triangle me-2"></i>{{ session('warning') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if(session('import_errors'))
    <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0" role="alert" style="border-radius: 12px;">
        <i class="bi bi-x-circle me-2"></i><strong>Import Errors:</strong>
        <ul class="mb-0 mt-2">
            @foreach(session('import_errors') as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="card border-0 shadow-sm" style="border-radius: 16px;">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-sm mb-0 members-table">
                <thead class="table-light" style="background: #FFFFFF; border-bottom: 3px solid #FFD700; color: #000000;">
                    <tr>
                        <th style="color: #000000;">#</th>
                        <th style="color: #000000;">Member No</th>
                        <th style="color: #000000;">Name</th>
                        <th style="color: #000000;">Phone</th>
                        <th style="color: #000000;">Registration</th>
                        <th style="color: #000000;">Date</th>
                        <th style="color: #000000;">Status</th>
                        <th class="text-center" style="color: #000000;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($members as $member)
                    <tr>
                        <td class="fw-semibold">{{ $loop->iteration }}</td>
                        <td><span class="badge members-badge" style="background: #FFD700; color: #000000;">QBM{{ $member->member_no }}</span></td>
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
                                <span class="badge members-badge" style="background: #FFD700; color: #000000;">
                                    <i class="bi bi-check-circle me-1"></i>Registered
                                </span>
                                <div class="small" style="color: #FFD700;">KES {{ number_format($member->registrationFeePaid ?? 0, 2) }} (in bank)</div>
                            @else
                                <span class="badge members-badge" style="background: #6c757d; color: #FFFFFF;">
                                    <i class="bi bi-exclamation-triangle me-1"></i>Not Registered
                                </span>
                                <div class="small text-muted">KES {{ number_format($member->registrationFeePaid ?? 0, 2) }} / 1,000.00</div>
                            @endif
                        </td>
                        <td>
                            <i class="bi bi-calendar3 me-1 text-muted"></i>
                            <span class="text-muted">{{ $member->created_at->format('M d, Y') }}</span>
                        </td>
                        <td>
                            @if($member->status === 'ACTIVE')
                                <span class="badge members-badge" style="background: #FFD700; color: #000000;"><i class="bi bi-check-circle me-1"></i>Active</span>
                            @else
                                <span class="badge members-badge bg-secondary"><i class="bi bi-x-circle me-1"></i>Inactive</span>
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

