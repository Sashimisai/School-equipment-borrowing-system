@extends('layouts.app')

@section('title', 'Borrow Equipment')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            @if($hasOverdue)
                <div class="alert alert-warning">
                    <i class="bi bi-exclamation-triangle"></i>
                    You have overdue equipment. Please return them before making new borrow requests.
                </div>
            @endif

            <div class="card">
                <div class="card-header">
                    <h4><i class="bi bi-box-arrow-down"></i> Borrow Equipment Request</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('borrowings.store') }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="equipment_id" class="form-label">Select Equipment *</label>
                            <select class="form-select @error('equipment_id') is-invalid @enderror" 
                                    id="equipment_id" name="equipment_id" required>
                                <option value="">Choose equipment...</option>
                                @foreach($availableEquipment as $equipment)
                                    <option value="{{ $equipment->id }}" {{ old('equipment_id') == $equipment->id ? 'selected' : '' }}>
                                        {{ $equipment->name }} ({{ $equipment->serial_number }}) - {{ $equipment->category }}
                                    </option>
                                @endforeach
                            </select>
                            @error('equipment_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="borrow_date" class="form-label">Borrow Date *</label>
                                <input type="date" class="form-control @error('borrow_date') is-invalid @enderror" 
                                       id="borrow_date" name="borrow_date" 
                                       value="{{ old('borrow_date', date('Y-m-d')) }}" 
                                       min="{{ date('Y-m-d') }}" required>
                                @error('borrow_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="return_date" class="form-label">Return Date *</label>
                                <input type="date" class="form-control @error('return_date') is-invalid @enderror" 
                                       id="return_date" name="return_date" 
                                       value="{{ old('return_date') }}" 
                                       min="{{ date('Y-m-d', strtotime('+1 day')) }}" required>
                                @error('return_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="remarks" class="form-label">Remarks/Reason for Borrowing</label>
                            <textarea class="form-control @error('remarks') is-invalid @enderror" 
                                      id="remarks" name="remarks" rows="3">{{ old('remarks') }}</textarea>
                            @error('remarks')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('borrowings.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Back
                            </a>
                            <button type="submit" class="btn btn-primary" {{ $hasOverdue ? 'disabled' : '' }}>
                                <i class="bi bi-send"></i> Submit Request
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.getElementById('borrow_date').addEventListener('change', function() {
        var borrowDate = new Date(this.value);
        var returnDate = new Date(borrowDate.getTime() + (24 * 60 * 60 * 1000)); // Next day
        document.getElementById('return_date').min = returnDate.toISOString().split('T')[0];
    });
</script>
@endsection