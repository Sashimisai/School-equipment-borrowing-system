@extends('layouts.app')

@section('title', 'User Management')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-people"></i> User Management</h2>
        <div>
            <span class="badge bg-warning fs-6">
                Pending Approvals: {{ $pendingCount }}
            </span>
        </div>
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-header">
            <h5><i class="bi bi-funnel"></i> Filters</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.users') }}" method="GET" id="filterForm">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label for="role" class="form-label">Role</label>
                        <select name="role" id="role" class="form-select" onchange="document.getElementById('filterForm').submit()">
                            <option value="">All Roles</option>
                            <option value="student" {{ request('role') == 'student' ? 'selected' : '' }}>Student</option>
                            <option value="officer" {{ request('role') == 'officer' ? 'selected' : '' }}>Student Officer</option>
                            <option value="professor" {{ request('role') == 'professor' ? 'selected' : '' }}>Professor</option>
                            <option value="dean" {{ request('role') == 'dean' ? 'selected' : '' }}>Dean</option>
                            <option value="staff" {{ request('role') == 'staff' ? 'selected' : '' }}>Staff</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="approval_status" class="form-label">Approval Status</label>
                        <select name="approval_status" id="approval_status" class="form-select" onchange="document.getElementById('filterForm').submit()">
                            <option value="">All Status</option>
                            <option value="pending" {{ request('approval_status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="approved" {{ request('approval_status') == 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="rejected" {{ request('approval_status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">&nbsp;</label>
                        <div>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-search"></i> Apply Filters
                            </button>
                            <a href="{{ route('admin.users') }}" class="btn btn-secondary">
                                <i class="bi bi-x-circle"></i> Clear Filters
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Results Summary -->
    <div class="alert alert-info">
        <i class="bi bi-info-circle"></i>
        @if(request('role') || request('approval_status'))
            Showing results for: 
            @if(request('role'))
                <strong>Role: {{ ucfirst(request('role')) }}</strong> 
            @endif
            @if(request('approval_status'))
                <strong>Status: {{ ucfirst(request('approval_status')) }}</strong>
            @endif
            <br>
        @endif
        Total Records Found: <strong>{{ $users->total() }}</strong>
    </div>

    <!-- Users Table -->
    <div class="card">
        <div class="card-body">
            @if($users->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Position</th>
                                <th>Status</th>
                                <th>Registered</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                            <tr>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    @php
                                        $roleColors = [
                                            'dean' => 'danger',
                                            'professor' => 'primary',
                                            'officer' => 'success',
                                            'student' => 'info',
                                            'staff' => 'warning'
                                        ];
                                        $roleColor = $roleColors[$user->role] ?? 'secondary';
                                    @endphp
                                    <span class="badge bg-{{ $roleColor }}">
                                        {{ ucfirst($user->role) }}
                                    </span>
                                </td>
                                <td>{{ $user->position ?? 'N/A' }}</td>
                                <td>
                                    @php
                                        $statusColors = [
                                            'approved' => 'success',
                                            'pending' => 'warning',
                                            'rejected' => 'danger'
                                        ];
                                        $statusColor = $statusColors[$user->approval_status] ?? 'secondary';
                                    @endphp
                                    <span class="badge bg-{{ $statusColor }}">
                                        {{ ucfirst($user->approval_status) }}
                                    </span>
                                </td>
                                <td>{{ $user->created_at->format('M d, Y') }}</td>
                                <td>
                                    {{-- PENDING STATUS: Show Approve and Reject buttons --}}
                                    @if($user->approval_status == 'pending')
                                        <div class="btn-group" role="group">
                                            <button class="btn btn-success btn-sm" data-bs-toggle="modal" 
                                                    data-bs-target="#approveModal{{ $user->id }}"
                                                    title="Approve this user">
                                                <i class="bi bi-check-lg"></i> Approve
                                            </button>
                                            <button class="btn btn-danger btn-sm" data-bs-toggle="modal" 
                                                    data-bs-target="#rejectModal{{ $user->id }}"
                                                    title="Reject this user">
                                                <i class="bi bi-x-lg"></i> Reject
                                            </button>
                                        </div>
                                    @endif
                                    
                                    {{-- APPROVED STATUS: Show Edit, Hold, and Delete buttons --}}
                                    @if($user->approval_status == 'approved')
                                        <div class="btn-group" role="group">
                                            <button class="btn btn-warning btn-sm" data-bs-toggle="modal" 
                                                    data-bs-target="#editModal{{ $user->id }}"
                                                    title="Edit user information">
                                                <i class="bi bi-pencil"></i> Edit
                                            </button>
                                            <button class="btn btn-secondary btn-sm" data-bs-toggle="modal" 
                                                    data-bs-target="#holdModal{{ $user->id }}"
                                                    title="Hold/Suspend user account">
                                                <i class="bi bi-pause-circle"></i> Hold
                                            </button>
                                            <button class="btn btn-danger btn-sm" data-bs-toggle="modal" 
                                                    data-bs-target="#deleteModal{{ $user->id }}"
                                                    title="Delete user permanently">
                                                <i class="bi bi-trash"></i> Delete
                                            </button>
                                        </div>
                                    @endif
                                    
                                    {{-- REJECTED STATUS: Show only Delete button --}}
                                    @if($user->approval_status == 'rejected')
                                        <div class="btn-group" role="group">
                                            <button class="btn btn-danger btn-sm" data-bs-toggle="modal" 
                                                    data-bs-target="#deleteModal{{ $user->id }}"
                                                    title="Delete user permanently">
                                                <i class="bi bi-trash"></i> Delete
                                            </button>
                                        </div>
                                    @endif
                                </td>
                            </tr>

                            {{-- APPROVE MODAL --}}
                            @if($user->approval_status == 'pending')
                            <div class="modal fade" id="approveModal{{ $user->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form action="{{ route('admin.users.approve', $user) }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="approval_status" value="approved">
                                            <div class="modal-header bg-success text-white">
                                                <h5 class="modal-title">
                                                    <i class="bi bi-check-circle"></i> Approve User Account
                                                </h5>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="alert alert-info">
                                                    <i class="bi bi-info-circle"></i>
                                                    You are about to approve this user account. They will be able to login after approval.
                                                </div>
                                                <div class="card mb-3">
                                                    <div class="card-body">
                                                        <p><strong>Name:</strong> {{ $user->name }}</p>
                                                        <p><strong>Email:</strong> {{ $user->email }}</p>
                                                        <p><strong>Role:</strong> {{ ucfirst($user->role) }}</p>
                                                        @if($user->position)
                                                            <p><strong>Position:</strong> {{ $user->position }}</p>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Remarks (Optional)</label>
                                                    <textarea class="form-control" name="admin_remarks" rows="2" 
                                                              placeholder="Add any notes about this approval"></textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                    <i class="bi bi-x-circle"></i> Cancel
                                                </button>
                                                <button type="submit" class="btn btn-success">
                                                    <i class="bi bi-check-lg"></i> Approve User
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            {{-- REJECT MODAL --}}
                            <div class="modal fade" id="rejectModal{{ $user->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form action="{{ route('admin.users.approve', $user) }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="approval_status" value="rejected">
                                            <div class="modal-header bg-danger text-white">
                                                <h5 class="modal-title">
                                                    <i class="bi bi-x-circle"></i> Reject User Account
                                                </h5>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="alert alert-warning">
                                                    <i class="bi bi-exclamation-triangle"></i>
                                                    You are about to reject this user account. They will NOT be able to login.
                                                </div>
                                                <div class="card mb-3">
                                                    <div class="card-body">
                                                        <p><strong>Name:</strong> {{ $user->name }}</p>
                                                        <p><strong>Email:</strong> {{ $user->email }}</p>
                                                        <p><strong>Role:</strong> {{ ucfirst($user->role) }}</p>
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Reason for Rejection <span class="text-danger">*</span></label>
                                                    <textarea class="form-control" name="admin_remarks" rows="3" required 
                                                              placeholder="Please provide a detailed reason for rejection"></textarea>
                                                    <small class="text-muted">This reason will be shown to the user.</small>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                    <i class="bi bi-x-circle"></i> Cancel
                                                </button>
                                                <button type="submit" class="btn btn-danger">
                                                    <i class="bi bi-x-lg"></i> Reject User
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @endif

                            {{-- EDIT MODAL (for Approved users) --}}
                            @if($user->approval_status == 'approved')
                            <div class="modal fade" id="editModal{{ $user->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form action="{{ route('admin.users.updateRole', $user) }}" method="POST">
                                            @csrf
                                            <div class="modal-header bg-warning">
                                                <h5 class="modal-title">
                                                    <i class="bi bi-pencil"></i> Edit User Information
                                                </h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label class="form-label">Full Name</label>
                                                    <input type="text" class="form-control" value="{{ $user->name }}" readonly>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Email</label>
                                                    <input type="email" class="form-control" value="{{ $user->email }}" readonly>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Role <span class="text-danger">*</span></label>
                                                    <select name="role" class="form-select" required>
                                                        <option value="student" {{ $user->role == 'student' ? 'selected' : '' }}>Student</option>
                                                        <option value="officer" {{ $user->role == 'officer' ? 'selected' : '' }}>Student Officer</option>
                                                        <option value="professor" {{ $user->role == 'professor' ? 'selected' : '' }}>Professor</option>
                                                        <option value="dean" {{ $user->role == 'dean' ? 'selected' : '' }}>Dean</option>
                                                        <option value="staff" {{ $user->role == 'staff' ? 'selected' : '' }}>Staff</option>
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Position/Title</label>
                                                    <input type="text" name="position" class="form-control" 
                                                           value="{{ $user->position }}" 
                                                           placeholder="Enter position or title">
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                    <i class="bi bi-x-circle"></i> Cancel
                                                </button>
                                                <button type="submit" class="btn btn-warning">
                                                    <i class="bi bi-save"></i> Save Changes
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            {{-- HOLD MODAL (for Approved users) --}}
                            <div class="modal fade" id="holdModal{{ $user->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form action="{{ route('admin.users.approve', $user) }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="approval_status" value="pending">
                                            <div class="modal-header bg-secondary text-white">
                                                <h5 class="modal-title">
                                                    <i class="bi bi-pause-circle"></i> Hold User Account
                                                </h5>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="alert alert-warning">
                                                    <i class="bi bi-exclamation-triangle"></i>
                                                    You are about to put this user account on hold. They will NOT be able to use the system until un-held.
                                                </div>
                                                <div class="card mb-3">
                                                    <div class="card-body">
                                                        <p><strong>Name:</strong> {{ $user->name }}</p>
                                                        <p><strong>Email:</strong> {{ $user->email }}</p>
                                                        <p><strong>Role:</strong> {{ ucfirst($user->role) }}</p>
                                                        @if($user->position)
                                                            <p><strong>Position:</strong> {{ $user->position }}</p>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Reason for Hold</label>
                                                    <textarea class="form-control" name="admin_remarks" rows="2" 
                                                              placeholder="Why is this account being put on hold?"></textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                    <i class="bi bi-x-circle"></i> Cancel
                                                </button>
                                                <button type="submit" class="btn btn-secondary">
                                                    <i class="bi bi-pause-circle"></i> Hold Account
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @endif

                            {{-- DELETE MODAL (for Approved and Rejected users) --}}
                            @if($user->approval_status == 'approved' || $user->approval_status == 'rejected')
                            <div class="modal fade" id="deleteModal{{ $user->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form action="{{ route('admin.users.delete', $user) }}" method="POST" id="deleteForm{{ $user->id }}">
                                            @csrf
                                            @method('DELETE')
                                            <div class="modal-header bg-danger text-white">
                                                <h5 class="modal-title">
                                                    <i class="bi bi-trash"></i> Delete User Account
                                                </h5>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="alert alert-danger">
                                                    <i class="bi bi-exclamation-triangle-fill"></i>
                                                    <strong>Warning:</strong> This action cannot be undone! All data associated with this user will be permanently deleted.
                                                </div>
                                                <div class="card mb-3">
                                                    <div class="card-body">
                                                        <p><strong>Name:</strong> {{ $user->name }}</p>
                                                        <p><strong>Email:</strong> {{ $user->email }}</p>
                                                        <p><strong>Role:</strong> {{ ucfirst($user->role) }}</p>
                                                        <p><strong>Status:</strong> {{ ucfirst($user->approval_status) }}</p>
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Type "DELETE" to confirm</label>
                                                    <input type="text" class="form-control" 
                                                           onkeyup="document.getElementById('confirmDeleteBtn{{ $user->id }}').disabled = this.value !== 'DELETE'"
                                                           placeholder="DELETE">
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                    <i class="bi bi-x-circle"></i> Cancel
                                                </button>
                                                <button type="submit" class="btn btn-danger" id="confirmDeleteBtn{{ $user->id }}" disabled>
                                                    <i class="bi bi-trash"></i> Delete Permanently
                                                </button>
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

                <!-- Pagination -->
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <div>
                        Showing {{ $users->firstItem() ?? 0 }} to {{ $users->lastItem() ?? 0 }} of {{ $users->total() }} results
                    </div>
                    <div>
                        {{ $users->appends(request()->query())->links() }}
                    </div>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-people" style="font-size: 4rem; color: #ccc;"></i>
                    <h4 class="mt-3 text-muted">No users found</h4>
                    <p class="text-muted">Try adjusting your filters or add new users.</p>
                    <a href="{{ route('admin.users') }}" class="btn btn-primary">
                        <i class="bi bi-x-circle"></i> Clear Filters
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });
        
        console.log('Admin Users Management loaded');
        console.log('Current filters - Role: {{ request('role', 'none') }}, Status: {{ request('approval_status', 'none') }}');
    });
</script>
@endsection