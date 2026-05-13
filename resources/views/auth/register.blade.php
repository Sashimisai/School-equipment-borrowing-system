@extends('layouts.app')

@section('title', 'Register')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4><i class="bi bi-person-plus"></i> Register New Account</h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="row mb-3">
                            <label for="name" class="col-md-4 col-form-label text-md-end">Full Name *</label>
                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" 
                                       name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
                                @error('name')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="email" class="col-md-4 col-form-label text-md-end">Email Address *</label>
                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" 
                                       name="email" value="{{ old('email') }}" required autocomplete="email">
                                @error('email')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="role" class="col-md-4 col-form-label text-md-end">Role *</label>
                            <div class="col-md-6">
                                <select id="role" class="form-select @error('role') is-invalid @enderror" 
                                        name="role" required onchange="togglePositionField()">
                                    <option value="">-- Select Role --</option>
                                    <option value="student" {{ old('role') == 'student' ? 'selected' : '' }}>Student</option>
                                    <option value="officer" {{ old('role') == 'officer' ? 'selected' : '' }}>Student Officer</option>
                                    <option value="professor" {{ old('role') == 'professor' ? 'selected' : '' }}>Professor</option>
                                    <option value="dean" {{ old('role') == 'dean' ? 'selected' : '' }}>Dean</option>
                                </select>
                                @error('role')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3" id="positionField" style="display: none;">
                            <label for="position" class="col-md-4 col-form-label text-md-end">Position/Title</label>
                            <div class="col-md-6">
                                <input id="position" type="text" class="form-control @error('position') is-invalid @enderror" 
                                       name="position" value="{{ old('position') }}" 
                                       placeholder="e.g., President, Secretary, Dean of Engineering">
                                <small class="text-muted">Required for Officer, Professor, and Dean roles</small>
                                @error('position')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="password" class="col-md-4 col-form-label text-md-end">Password *</label>
                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" 
                                       name="password" required autocomplete="new-password">
                                @error('password')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-end">Confirm Password *</label>
                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" 
                                       name="password_confirmation" required autocomplete="new-password">
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-person-plus"></i> Register
                                </button>
                                <a href="{{ route('login') }}" class="btn btn-link">
                                    Already have an account? Login
                                </a>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-6 offset-md-4">
                                <div class="alert alert-info">
                                    <i class="bi bi-info-circle"></i>
                                    <strong>Note:</strong> All new accounts require admin approval before you can login.
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function togglePositionField() {
        var role = document.getElementById('role').value;
        var positionField = document.getElementById('positionField');
        
        if (role === 'officer' || role === 'professor' || role === 'dean') {
            positionField.style.display = 'flex';
            document.getElementById('position').required = true;
        } else {
            positionField.style.display = 'none';
            document.getElementById('position').required = false;
        }
    }

    // Check on page load
    document.addEventListener('DOMContentLoaded', function() {
        togglePositionField();
    });
</script>
@endsection