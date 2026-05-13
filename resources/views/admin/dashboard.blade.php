@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="container-fluid">
    <h2 class="mb-4"><i class="bi bi-speedometer2"></i> Admin Dashboard</h2>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title">Total Equipment</h6>
                            <h2>{{ $stats['totalEquipment'] }}</h2>
                        </div>
                        <i class="bi bi-tools" style="font-size: 3rem;"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title">Available</h6>
                            <h2>{{ $stats['availableEquipment'] }}</h2>
                        </div>
                        <i class="bi bi-check-circle" style="font-size: 3rem;"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title">Pending Requests</h6>
                            <h2>{{ $stats['pendingRequests'] }}</h2>
                        </div>
                        <i class="bi bi-hourglass-split" style="font-size: 3rem;"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title">Total Users</h6>
                            <h2>{{ $stats['totalUsers'] }}</h2>
                        </div>
                        <i class="bi bi-people" style="font-size: 3rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- More Stats -->
    <div class="row mb-4">
        <div class="col-md-4 mb-3">
            <div class="card">
                <div class="card-header">
                    <h5>Equipment Status</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Available
                            <span class="badge bg-success">{{ $stats['availableEquipment'] }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Borrowed
                            <span class="badge bg-warning">{{ $stats['borrowedEquipment'] }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Maintenance
                            <span class="badge bg-danger">{{ $stats['maintenanceEquipment'] }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card">
                <div class="card-header">
                    <h5>Request Statistics</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Pending
                            <span class="badge bg-warning">{{ $stats['pendingRequests'] }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Approved
                            <span class="badge bg-success">{{ $stats['approvedRequests'] }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Rejected
                            <span class="badge bg-danger">{{ $stats['rejectedRequests'] }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Returned
                            <span class="badge bg-info">{{ $stats['returnedRequests'] }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
            <div class="col-md-3 mb-3">
                <div class="card bg-danger text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title">Pending Users</h6>
                                <h2>{{ \App\Models\User::where('approval_status', 'pending')->where('role', '!=', 'admin')->count() }}</h2>
                            </div>
                            <i class="bi bi-person-x" style="font-size: 3rem;"></i>
                        </div>
                    </div>
                    <a href="{{ route('admin.users', ['approval_status' => 'pending']) }}" class="text-white text-decoration-none">
                        <div class="card-footer text-center">
                            View Pending Users <i class="bi bi-arrow-right"></i>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Pending Requests -->
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5><i class="bi bi-hourglass-split"></i> Pending Requests</h5>
            <a href="{{ route('admin.requests', ['status' => 'pending']) }}" class="btn btn-sm btn-primary">View All</a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Equipment</th>
                            <th>Borrow Date</th>
                            <th>Return Date</th>
                            <th>Request Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pendingRequests as $request)
                        <tr>
                            <td>{{ $request->user->name }}</td>
                            <td>{{ $request->equipment->name }}</td>
                            <td>{{ $request->borrow_date->format('M d, Y') }}</td>
                            <td>{{ $request->return_date->format('M d, Y') }}</td>
                            <td>{{ $request->created_at->diffForHumans() }}</td>
                            <td>
                                <a href="{{ route('admin.requests') }}" class="btn btn-sm btn-success">Review</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Recent Borrowings -->
    <div class="card">
        <div class="card-header">
            <h5><i class="bi bi-clock-history"></i> Recent Borrowings</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Equipment</th>
                            <th>Status</th>
                            <th>Borrow Date</th>
                            <th>Return Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentBorrowings as $borrowing)
                        <tr>
                            <td>{{ $borrowing->user->name }}</td>
                            <td>{{ $borrowing->equipment->name }}</td>
                            <td>
                                <span class="badge {{ $borrowing->statusBadge }}">
                                    {{ ucfirst($borrowing->status) }}
                                </span>
                            </td>
                            <td>{{ $borrowing->borrow_date->format('M d, Y') }}</td>
                            <td>{{ $borrowing->return_date->format('M d, Y') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection