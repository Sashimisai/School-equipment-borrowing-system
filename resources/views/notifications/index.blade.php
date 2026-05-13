@extends('layouts.app')

@section('title', 'Notifications')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-bell"></i> Notifications</h2>
        <form action="{{ route('notifications.markAllRead') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-success">
                <i class="bi bi-check-all"></i> Mark All as Read
            </button>
        </form>
    </div>

    <div class="card">
        <div class="card-body">
            @forelse($notifications as $notification)
                <div class="notification-item p-3 border-bottom {{ $notification->is_read ? '' : 'bg-light' }}">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="mb-1 {{ $notification->is_read ? '' : 'fw-bold' }}">
                                @switch($notification->type)
                                    @case('borrow_approved')
                                        <i class="bi bi-check-circle text-success"></i>
                                        @break
                                    @case('borrow_rejected')
                                        <i class="bi bi-x-circle text-danger"></i>
                                        @break
                                    @case('borrow_returned')
                                        <i class="bi bi-box-arrow-in-right text-info"></i>
                                        @break
                                    @default
                                        <i class="bi bi-bell"></i>
                                @endswitch
                                {{ $notification->message }}
                            </p>
                            <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                        </div>
                        @if(!$notification->is_read)
                            <button class="btn btn-sm btn-outline-primary mark-read" 
                                    data-id="{{ $notification->id }}">
                                Mark Read
                            </button>
                        @endif
                    </div>
                </div>
            @empty
                <div class="text-center py-5">
                    <i class="bi bi-bell-slash" style="font-size: 3rem;"></i>
                    <p class="text-muted mt-3">No notifications</p>
                </div>
            @endforelse

            <div class="d-flex justify-content-center mt-4">
                {{ $notifications->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('.mark-read').click(function() {
            var id = $(this).data('id');
            var button = $(this);
            
            $.post('/notifications/' + id + '/mark-as-read', function(response) {
                if (response.success) {
                    button.closest('.notification-item').removeClass('bg-light');
                    button.closest('.fw-bold').removeClass('fw-bold');
                    button.remove();
                    updateNotificationCount();
                }
            });
        });
    });
</script>
@endsection