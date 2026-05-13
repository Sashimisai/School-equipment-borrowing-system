@extends('layouts.app')

@section('title', 'Borrow Requests')

@section('content')
<div class="container-fluid">
    <h2 class="mb-4"><i class="bi bi-list-check"></i> Borrow Requests Management</h2>

    <div class="card">
        <div class="card-body">
            <!-- Filters -->
            <form action="{{ route('admin.requests') }}" method="GET" class="row g-3 mb-4">
                <div class="col-md-3">
                    <input type="text" name="search" class="form-control" 
                           placeholder="Search user or equipment..." value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-select">
                        <option value="">All Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        <option value="returned" {{ request('status') == 'returned' ? 'selected' : '' }}>Returned</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                </div>
                <div class="col-md-2">
                    <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary me-2">Filter</button>
                    <a href="{{ route('admin.requests') }}" class="btn btn-secondary">Reset</a>
                </div>
            </form>

            <!-- Requests Table -->
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>User</th>
                            <th>Equipment</th>
                            <th>Borrow Date</th>
                            <th>Return Date</th>
                            <th>Status</th>
                            <th>Remarks</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($borrowings as $borrowing)
                        <tr>
                            <td>#{{ $borrowing->id }}</td>
                            <td>
                                <strong>{{ $borrowing->user->name }}</strong><br>
                                <small class="text-muted">{{ $borrowing->user->email }}</small>
                            </td>
                            <td>{{ $borrowing->equipment->name }}</td>
                            <td>{{ $borrowing->borrow_date->format('M d, Y') }}</td>
                            <td>{{ $borrowing->return_date->format('M d, Y') }}</td>
                            <td>
                                @php
                                    $badgeClass = match($borrowing->status) {
                                        'pending' => 'bg-warning text-dark',
                                        'approved' => 'bg-success',
                                        'rejected' => 'bg-danger',
                                        'returned' => 'bg-info',
                                        default => 'bg-secondary'
                                    };
                                @endphp
                                <span class="badge {{ $badgeClass }}">
                                    {{ ucfirst($borrowing->status) }}
                                </span>
                            </td>
                            <td>{{ $borrowing->remarks ?? 'N/A' }}</td>
                            <td>
                                @if($borrowing->status == 'pending')
                                    <button class="btn btn-success btn-sm" data-bs-toggle="modal" 
                                            data-bs-target="#approveModal{{ $borrowing->id }}">
                                        <i class="bi bi-check-lg"></i> Approve
                                    </button>
                                    <button class="btn btn-danger btn-sm" data-bs-toggle="modal" 
                                            data-bs-target="#rejectModal{{ $borrowing->id }}">
                                        <i class="bi bi-x-lg"></i> Reject
                                    </button>
                                @elseif($borrowing->status == 'approved')
                                    <form action="{{ route('admin.approve', $borrowing) }}" method="POST" class="d-inline">
                                        @csrf
                                        <input type="hidden" name="action" value="returned">
                                        <button type="submit" class="btn btn-info btn-sm" 
                                                onclick="return confirm('Mark as returned?')">
                                            <i class="bi bi-box-arrow-in-right"></i> Returned
                                        </button>
                                    </form>
                                @else
                                    <span class="text-muted">No actions</span>
                                @endif
                            </td>
                        </tr>

                        {{-- APPROVE MODAL --}}
                        @if($borrowing->status == 'pending')
                        <div class="modal fade" id="approveModal{{ $borrowing->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form action="{{ route('admin.approve', $borrowing) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="action" value="approved">
                                        
                                        <div class="modal-header bg-success text-white">
                                            <h5 class="modal-title">Approve Borrow Request</h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p>Approve request for <strong>{{ $borrowing->equipment->name }}</strong> by <strong>{{ $borrowing->user->name }}</strong>?</p>
                                            <div class="mb-3">
                                                <label class="form-label">Remarks (Optional)</label>
                                                <textarea class="form-control" name="admin_remarks" rows="2"></textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-success">Approve</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        {{-- REJECT MODAL --}}
                        <div class="modal fade" id="rejectModal{{ $borrowing->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form action="{{ route('admin.approve', $borrowing) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="action" value="rejected">
                                        
                                        <div class="modal-header bg-danger text-white">
                                            <h5 class="modal-title">Reject Borrow Request</h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p>Reject request for <strong>{{ $borrowing->equipment->name }}</strong> by <strong>{{ $borrowing->user->name }}</strong>?</p>
                                            <div class="mb-3">
                                                <label class="form-label">Reason for Rejection <span class="text-danger">*</span></label>
                                                <textarea class="form-control" name="admin_remarks" rows="3" required></textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-danger">Reject</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @endif
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center mt-4">
                {{ $borrowings->links() }}
            </div>
        </div>
    </div>
</div>
@endsection