@extends('admin.admin_master')
@section('admin')

    <!--start page wrapper -->
    <div class="page-wrapper">
        <div class="page-content">
            <!--breadcrumb-->
            <div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">All Notifications</h4>
                    <div class="btn-group">
                        <button class="btn btn-sm btn-outline-primary" onclick="markAllAsRead()">
                            Mark All as Read
                        </button>
                        <a href="{{ route('notification-settings.show') }}" class="btn btn-sm btn-outline-secondary">
                            <i class="fas fa-cog"></i> Settings
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    @forelse($notifications as $notification)
                        <div class="notification-item border-bottom p-3 {{ $notification->read_at ? '' : 'bg-light' }}"
                             data-notification-id="{{ $notification->id }}">
                            <div class="d-flex align-items-start">
                                <div class="notification-icon me-3 mt-1">
                                    @switch($notification->type)
                                        @case('App\Notifications\LeaveRequestSubmitted')
                                            <i class="fas fa-calendar-plus text-primary fa-lg"></i>
                                            @break
                                        @case('App\Notifications\LeaveRequestStatusChanged')
                                            @if(isset($notification->data['status']) && $notification->data['status'] === 'approved')
                                                <i class="fas fa-check-circle text-success fa-lg"></i>
                                            @else
                                                <i class="fas fa-times-circle text-danger fa-lg"></i>
                                            @endif
                                            @break
                                        @case('App\Notifications\LeaveBalanceReset')
                                            <i class="fas fa-refresh text-info fa-lg"></i>
                                            @break
                                        @case('App\Notifications\LeaveReminder')
                                            <i class="fas fa-clock text-warning fa-lg"></i>
                                            @break
                                        @case('App\Notifications\LeaveBalanceLow')
                                            <i class="fas fa-exclamation-triangle text-warning fa-lg"></i>
                                            @break
                                        @default
                                            <i class="fas fa-bell text-secondary fa-lg"></i>
                                    @endswitch
                                </div>

                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between">
                                        <h6 class="mb-1">
                                            {{ $this->getNotificationTitle($notification) }}
                                            @unless($notification->read_at)
                                                <span class="badge bg-primary ms-2">New</span>
                                            @endunless
                                        </h6>
                                        <small class="text-muted">
                                            {{ $notification->created_at->diffForHumans() }}
                                        </small>
                                    </div>

                                    <p class="mb-2 text-muted">
                                        {{ $notification->data['message'] ?? 'New notification' }}
                                    </p>

                                    @if(isset($notification->data['leave_request_id']))
                                        <a href="{{ route('leave-requests.show', $notification->data['leave_request_id']) }}"
                                           class="btn btn-sm btn-outline-primary">
                                            View Details
                                        </a>
                                    @endif

                                    @unless($notification->read_at)
                                        <button class="btn btn-sm btn-link text-muted p-0 ms-2"
                                                onclick="markAsRead('{{ $notification->id }}')">
                                            Mark as read
                                        </button>
                                    @endunless
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-5">
                            <i class="fas fa-bell-slash fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No notifications yet</h5>
                            <p class="text-muted">When you receive notifications, they'll appear here.</p>
                        </div>
                    @endforelse
                </div>

                @if($notifications->hasPages())
                    <div class="card-footer">
                        {{ $notifications->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
function markAllAsRead() {
    fetch('{{ route("notifications.read-all") }}', {
        method: 'PATCH',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    });
}

function markAsRead(notificationId) {
    fetch(`/notifications/${notificationId}/read`, {
        method: 'PATCH',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const item = document.querySelector(`[data-notification-id="${notificationId}"]`);
            item.classList.remove('bg-light');
            item.querySelector('.badge')?.remove();
            item.querySelector('[onclick*="markAsRead"]')?.remove();
        }
    });
}
</script>


            <!-- end of body -->
        </div>
    </div>

    <!--end page wrapper -->

@endsection
