@extends('layouts.app')

@section('title', 'Equipment List')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-tools"></i> Equipment Management</h2>
        @if(auth()->user()->isAdmin() || auth()->user()->isStaff())
            <a href="{{ route('equipment.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg"></i> Add New Equipment
            </a>
        @endif
    </div>

    <div class="card">
        <div class="card-body">
            <!-- Filters -->
            <form action="{{ route('equipment.index') }}" method="GET" class="mb-4">
                <div class="row g-3">
                    <div class="col-md-4">
                        <input type="text" name="search" class="form-control" 
                               placeholder="Search by name, serial number..." 
                               value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <select name="category" class="form-select">
                            <option value="">All Categories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category }}" {{ request('category') == $category ? 'selected' : '' }}>
                                    {{ ucfirst($category) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="status" class="form-select">
                            <option value="">All Status</option>
                            <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>Available</option>
                            <option value="borrowed" {{ request('status') == 'borrowed' ? 'selected' : '' }}>Borrowed</option>
                            <option value="maintenance" {{ request('status') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-search"></i> Filter
                        </button>
                    </div>
                </div>
            </form>

            <!-- Equipment Table -->
            <div class="table-responsive">
                <table class="table table-hover" id="equipmentTable">
                    <thead class="table-dark">
                        <tr>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Category</th>
                            <th>Serial Number</th>
                            <th>Quantity</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($equipment as $item)
                            <tr>
                                <td>
                                    @if($item->image)
                                        <img src="{{ $item->imageUrl }}" alt="{{ $item->name }}" 
                                             style="width: 50px; height: 50px; object-fit: cover;" class="rounded">
                                    @else
                                        <div class="bg-secondary text-white d-flex align-items-center justify-content-center rounded" 
                                             style="width: 50px; height: 50px;">
                                            <i class="bi bi-image"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>{{ $item->name }}</td>
                                <td><span class="badge bg-info">{{ ucfirst($item->category) }}</span></td>
                                <td>{{ $item->serial_number }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>
                                    <span class="badge bg-{{ $item->status == 'available' ? 'success' : ($item->status == 'borrowed' ? 'warning' : 'danger') }}">
                                        {{ ucfirst($item->status) }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('equipment.show', $item) }}" class="btn btn-sm btn-info">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    @if(auth()->user()->isAdmin() || auth()->user()->isStaff())
                                        <a href="{{ route('equipment.edit', $item) }}" class="btn btn-sm btn-warning">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        @if(auth()->user()->isAdmin())
                                            <form action="{{ route('equipment.destroy', $item) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" 
                                                        onclick="return confirm('Are you sure you want to delete this equipment?')">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">No equipment found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center mt-4">
                {{ $equipment->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('#equipmentTable').DataTable({
            paging: false,
            searching: false,
            info: false,
            order: [[1, 'asc']]
        });
    });
</script>
@endsection