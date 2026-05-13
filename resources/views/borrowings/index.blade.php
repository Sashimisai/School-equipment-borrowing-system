@extends('layouts.app')

@section('title', 'My Borrowings')

@section('content')
<div class="container-fluid">
    <h2 class="mb-4"><i class="bi bi-clock-history"></i> My Borrowing History</h2>

    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <form action="{{ route('borrowings.index') }}" method="GET" class="d-flex">
                    <select name="status" class="form-select me-2" style="width: 200px;">
                        <option value="">All Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        <option value="returned" {{ request('status') == 'returned' ? 'selected' : '' }}>Returned</option>
                    </select>
                    <button type="submit" class="btn btn-primary">Filter</button>
                </form>
                <a href="{{ route('borrowings.create') }}" class="btn btn-success">
                    <i class="bi bi-plus-lg"></i> New Borrow Request
                </a>
            </div>

            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>Equipment</th>
                            <th>Borrow Date</th>
                            <th>Return Date</th>
                            <th>Status</th>
                            <th>Remarks</th>
                            <th>Request Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($borrowings as $borrowing)
                            <tr>
                                <td>
                                    <strong>{{ $borrowing->equipment->name }}</strong><br>
                                    <small class="text-muted">{{ $borrowing->equipment->serial_number }}</small>
                                </td>
                                <td>{{ $borrowing->borrow_date->format('M d, Y') }}</td>
                                <td>{{ $borrowing->return_date->format('M d, Y') }}</td>
                                <td>
                                    <span class="badge {{ $borrowing->statusBadge }}">
                                        {{ ucfirst($borrowing->status) }}
                                    </span>
                                </td>
                                <td>{{ $borrowing->remarks ?? 'N/A' }}</td>
                                <td>{{ $borrowing->created_at->format('M d, Y H:i') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">No borrowing history found.</td>
                            </tr>
                        @endforelse
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