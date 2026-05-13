@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid">
    <h2 class="mb-4"><i class="bi bi-house"></i> My Dashboard</h2>

    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6>Available Equipment</h6>
                            <h2>{{ $availableEquipment }}</h2>
                        </div>
                        <i class="bi bi-box" style="font-size: 3rem;"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6>Active Borrowings</h6>
                            <h2>{{ $activeBorrowings->count() }}</h2>
                        </div>
                        <i class="bi bi-arrow-left-right" style="font-size: 3rem;"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6>Notifications</h6>
                            <h2>{{ $unreadNotifications }}</h2>
                        </div>
                        <i class="bi bi-bell" style="font-size: 3rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5><i class="bi bi-arrow-left-right"></i> Active Borrowings</h5>
                </div>
                <div class="card-body">
                    @forelse($activeBorrowings as $borrowing)
                        <div class="alert alert-{{ $borrowing->status == 'approved' ? 'success' : 'warning' }}">
                            <strong>{{ $borrowing->equipment->name }}</strong><br>
                            Status: {{ ucfirst($borrowing->status) }}<br>
                            Return Date: {{ $borrowing->return_date->format('M d, Y') }}
                        </div>
                    @empty
                        <p class="text-muted">No active borrowings.</p>
                    @endforelse
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5><i class="bi bi-clock-history"></i> Recent Borrowings</h5>
                </div>
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Equipment</th>
                                <th>Date</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentBorrowings as $borrowing)
                            <tr>
                                <td>{{ $borrowing->equipment->name }}</td>
                                <td>{{ $borrowing->created_at->format('M d, Y') }}</td>
                                <td>
                                    <span class="badge {{ $borrowing->statusBadge }}">
                                        {{ ucfirst($borrowing->status) }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection