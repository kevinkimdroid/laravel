@extends('layouts.app')

@section('title', 'Members')

@section('content')
<div class="card border-0 shadow-lg mb-4 position-relative overflow-hidden" style="background: #000000; border-bottom: 3px solid #D4AF37; border-radius: 16px;">
    <div class="position-absolute" style="top: -30px; right: -30px; width: 120px; height: 120px; background: rgba(212, 175, 55, 0.1); border-radius: 50%;"></div>
    <div class="card-body p-4 position-relative" style="z-index: 1;">
        <div class="d-flex justify-content-between align-items-center flex-wrap">
            <div>
                <h3 class="mb-3 fw-bold text-white" style="font-size: 1.5rem;">
                    <i class="bi bi-people-fill me-2" style="color: #D4AF37;"></i>Members Management
                </h3>
                <p class="text-white-50 mb-0">Total Members: <strong style="color: #D4AF37;">{{ $members->count() }}</strong></p>
            </div>
            <div class="mt-3 mt-md-0">
                <a href="{{ route('whatsapp.index') }}" class="btn btn-success me-2" style="background-color: #25D366; border-color: #25D366; border-radius: 8px;">
                    <i class="bi bi-whatsapp me-1"></i>WhatsApp Reminders
                </a>
                <a href="{{ route('members.import.form') }}" class="btn btn-outline-light me-2" style="border-color: #D4AF37; color: #D4AF37; border-radius: 8px;">
                    <i class="bi bi-upload me-1"></i>Import CSV
                </a>
                <a href="{{ route('members.update-phone') }}" class="btn btn-outline-light me-2" style="border-color: #D4AF37; color: #D4AF37; border-radius: 8px;">
                    <i class="bi bi-telephone-fill me-1"></i>Update Phones
                </a>
                <a href="{{ route('members.create') }}" class="btn" style="background: #D4AF37; color: #000000; border-radius: 8px;">
                    <i class="bi bi-plus-circle me-1"></i>Add Member
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Search Bar -->
<div class="card border-0 shadow-lg mb-4" style="border-radius: 16px; border-left: 4px solid #D4AF37;">
    <div class="card-body p-4">
        <form method="GET" action="{{ route('members.index') }}" class="row g-3 align-items-end">
            <div class="col-md-8">
                <label for="search" class="form-label fw-semibold">
                    <i class="bi bi-search me-2" style="color: #D4AF37;"></i>Search Members
                </label>
                <div class="input-group">
                    <span class="input-group-text" style="background: #f8f9fa; border-color: #D4AF37;">
                        <i class="bi bi-search" style="color: #D4AF37;"></i>
                    </span>
                    <input type="text" 
                           class="form-control" 
                           id="search" 
                           name="search" 
                           value="{{ request('search') }}" 
                           placeholder="Search by name, member number, phone, or initials..."
                           style="border-color: #D4AF37;">
                    @if(request('search'))
                        <a href="{{ route('members.index') }}" class="btn btn-outline-secondary" title="Clear search" style="border-color: #D4AF37;">
                            <i class="bi bi-x-lg"></i>
                        </a>
                    @endif
                </div>
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn w-100" style="background: #D4AF37; color: #000000; border-radius: 8px;">
                    <i class="bi bi-search me-1"></i>Search
                </button>
            </div>
        </form>
        @if(request('search'))
            <div class="mt-2">
                <small class="text-muted">
                    Showing results for: <strong>"{{ request('search') }}"</strong>
                    <a href="{{ route('members.index') }}" class="text-decoration-none ms-2" style="color: #D4AF37;">
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

<div class="card border-0 shadow-lg" style="border-radius: 16px;">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light" style="background: #000000; border-bottom: 3px solid #D4AF37; color: white;">
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
                        <td><span class="badge" style="background: #D4AF37; color: #000000;">{{ $member->member_no }}</span></td>
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
                                <span class="badge" style="background: #D4AF37; color: #000000;">
                                    <i class="bi bi-check-circle me-1"></i>Registered
                                </span>
                                <br>
                                <small style="color: #D4AF37;">KES {{ number_format($member->registrationFeePaid ?? 0, 2) }} (in bank)</small>
                            @else
                                <span class="badge" style="background: #6c757d; color: #FFFFFF;">
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
                                <span class="badge" style="background: #D4AF37; color: #000000;"><i class="bi bi-check-circle me-1"></i>Active</span>
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

