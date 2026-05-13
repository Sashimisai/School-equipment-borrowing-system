@extends('layouts.app')

@section('title', 'Equipment Details')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4><i class="bi bi-info-circle"></i> Equipment Details</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 text-center">
                            @if($equipment->image)
                                <img src="{{ $equipment->imageUrl }}" alt="{{ $equipment->name }}" 
                                     class="img-fluid rounded mb-3" style="max-height: 200px;">
                            @else
                                <div class="bg-secondary text-white d-flex align-items-center justify-content-center rounded mb-3" 
                                     style="width: 100%; height: 200px;">
                                    <i class="bi bi-image" style="font-size: 3rem;"></i>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-8">
                            <table class="table">
                                <tr>
                                    <th>Name:</th>
                                    <td>{{ $equipment->name }}</td>
                                </tr>
                                <tr>
                                    <th>Category:</th>
                                    <td><span class="badge bg-info">{{ ucfirst($equipment->category) }}</span></td>
                                </tr>
                                <tr>
                                    <th>Serial Number:</th>
                                    <td>{{ $equipment->serial_number }}</td>
                                </tr>
                                <tr>
                                    <th>Status:</th>
                                    <td>
                                        <span class="badge bg-{{ $equipment->status == 'available' ? 'success' : ($equipment->status == 'borrowed' ? 'warning' : 'danger') }}">
                                            {{ ucfirst($equipment->status) }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Quantity:</th>
                                    <td>{{ $equipment->quantity }}</td>
                                </tr>
                                @if($equipment->description)
                                <tr>
                                    <th>Description:</th>
                                    <td>{{ $equipment->description }}</td>
                                </tr>
                                @endif
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            @if($equipment->borrowings->count() > 0)
            <div class="card mt-4">
                <div class="card-header">
                    <h5><i class="bi bi-clock-history"></i> Borrowing History</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Borrower</th>
                                    <th>Borrow Date</th>
                                    <th>Return Date</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($equipment->borrowings as $borrowing)
                                <tr>
                                    <td>{{ $borrowing->user->name }}</td>
                                    <td>{{ $borrowing->borrow_date->format('M d, Y') }}</td>
                                    <td>{{ $borrowing->return_date->format('M d, Y') }}</td>
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
            @endif
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5><i class="bi bi-gear"></i> Actions</h5>
                </div>
                <div class="card-body">
                    <a href="{{ route('equipment.index') }}" class="btn btn-secondary w-100 mb-2">
                        <i class="bi bi-arrow-left"></i> Back to List
                    </a>
                    
                    @if(auth()->user()->isAdmin() || auth()->user()->isStaff())
                        <a href="{{ route('equipment.edit', $equipment) }}" class="btn btn-warning w-100 mb-2">
                            <i class="bi bi-pencil"></i> Edit Equipment
                        </a>
                    @endif
                    
                    @if($equipment->status == 'available' && (auth()->user()->isStudent()))
                        <a href="{{ route('borrowings.create') }}" class="btn btn-success w-100">
                            <i class="bi bi-box-arrow-down"></i> Borrow This Equipment
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection