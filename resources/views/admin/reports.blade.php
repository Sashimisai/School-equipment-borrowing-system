@extends('layouts.app')

@section('title', 'Borrow Requests')

@section('content')
<div class="container-fluid">
    <h2 class="mb-4"><i class="bi bi-list-check"></i> Borrow Requests Management</h2>

    <div class="card">
        <div class="card-body">
            
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
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($borrowings as $borrowing)
                        <tr>
                            <td>#{{ $borrowing->id }}</td>
                            <td>{{ $borrowing->user->name }}<br><small>{{ $borrowing->user->email }}</small></td>
                            <td>{{ $borrowing->equipment->name }}</td>
                            <td>{{ $borrowing->borrow_date->format('M d, Y') }}</td>
                            <td>{{ $borrowing->return_date->format('M d, Y') }}</td>
                            <td>
                                @if($borrowing->status == 'pending')
                                    <span class="badge bg-warning text-dark">Pending</span>
                                @elseif($borrowing->status == 'approved')
                                    <span class="badge bg-success">Approved</span>
                                @elseif($borrowing->status == 'rejected')
                                    <span class="badge bg-danger">Rejected</span>
                                @elseif($borrowing->status == 'returned')
                                    <span class="badge bg-info">Returned</span>
                                @endif
                            </td>
                            <td>
                                @if($borrowing->status == 'pending')
                                    <a href="{{ route('admin.approve', $borrowing) }}?action=approved" 
                                       class="btn btn-success btn-sm"
                                       onclick="return confirm('Approve this request?')">
                                        Approve
                                    </a>
                                    <a href="{{ route('admin.approve', $borrowing) }}?action=rejected" 
                                       class="btn btn-danger btn-sm"
                                       onclick="return confirm('Reject this request?')">
                                        Reject
                                    </a>
                                @elseif($borrowing->status == 'approved')
                                    <a href="{{ route('admin.approve', $borrowing) }}?action=returned" 
                                       class="btn btn-info btn-sm"
                                       onclick="return confirm('Mark as returned?')">
                                        Mark Returned
                                    </a>
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{ $borrowings->links() }}
            
        </div>
    </div>
</div>
@endsection