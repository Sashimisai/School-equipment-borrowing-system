@extends('layouts.app')

@section('title', 'Welcome')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">School Equipment Borrowing System</div>

                <div class="card-body text-center">
                    @guest
                        <h3>Welcome to the School Equipment Borrowing System</h3>
                        <p class="lead">Manage and borrow school equipment easily and efficiently.</p>
                        
                        <div class="mt-4">
                            <a href="{{ route('login') }}" class="btn btn-primary btn-lg me-2">
                                <i class="bi bi-box-arrow-in-right"></i> Login
                            </a>
                            <a href="{{ route('register') }}" class="btn btn-success btn-lg">
                                <i class="bi bi-person-plus"></i> Register
                            </a>
                        </div>
                    @else
                        <h3>Welcome back, {{ Auth::user()->name }}!</h3>
                        <p class="lead">
                            @if(Auth::user()->isAdmin())
                                You are logged in as Administrator
                            @elseif(Auth::user()->isStaff())
                                You are logged in as Staff
                            @else
                                You are logged in as Student/Teacher
                            @endif
                        </p>
                        
                        <div class="mt-4">
                            @if(Auth::user()->isAdmin() || Auth::user()->isStaff())
                                <a href="{{ route('admin.dashboard') }}" class="btn btn-primary btn-lg">
                                    <i class="bi bi-speedometer2"></i> Go to Dashboard
                                </a>
                            @else
                                <a href="{{ route('dashboard') }}" class="btn btn-primary btn-lg">
                                    <i class="bi bi-house"></i> Go to Dashboard
                                </a>
                            @endif
                        </div>
                    @endguest
                </div>
            </div>
        </div>
    </div>
</div>
@endsection