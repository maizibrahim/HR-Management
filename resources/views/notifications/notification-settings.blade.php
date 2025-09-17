@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Notification Settings</h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('notification-settings.update') }}">
                        @csrf
                        @method('PATCH')

                        <div class="row">
                            <div class="col-md-6">
                                <h5 class="text-primary">Email Notifications</h5>
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox"
                                           name="email_leave_requests" value="1"
                                           {{ old('email_leave_requests', $user->notification_preferences['email_leave_requests'] ?? true) ? 'checked' : '' }}>
                                    <label class="form-check-label">
                                        <strong>Leave Request Submissions</strong>
                                        <small class="d-block text-muted">Get notified when subordinates submit leave requests</small>
                                    </label>
                                </div>

                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox"
                                           name="email_leave_status" value="1"
                                           {{ old('email_leave_status', $user->notification_preferences['email_leave_status'] ?? true) ? 'checked' : '' }}>
                                    <label class="form-check-label">
                                        <strong>Leave Status Updates</strong>
                                        <small class="d-block text-muted">Get notified when your leave requests are approved/rejected</small>
                                    </label>
                                </div>

                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox"
                                           name="email_leave_reminders" value="1"
                                           {{ old('email_leave_reminders', $user->notification_preferences['email_leave_reminders'] ?? true) ? 'checked' : '' }}>
                                    <label class="form-check-label">
                                        <strong>Leave Reminders</strong>
                                        <small class="d-block text-muted">Get reminded about upcoming leave dates</small>
                                    </label>
                                </div>

                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox"
                                           name="email_leave_balance" value="1"
                                           {{ old('email_leave_balance', $user->notification_preferences['email_leave_balance'] ?? true) ? 'checked' : '' }}>
                                    <label class="form-check-label">
                                        <strong>Leave Balance Updates</strong>
                                        <small class="d-block text-muted">Get notified about balance resets and low balances</small>
                                    </label>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <h5 class="text-success">In-App Notifications</h5>
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox"
                                           name="push_leave_requests" value="1"
                                           {{ old('push_leave_requests', $user->notification_preferences['push_leave_requests'] ?? true) ? 'checked' : '' }}>
                                    <label class="form-check-label">
                                        <strong>Leave Request Submissions</strong>
                                        <small class="d-block text-muted">Show in-app notifications for new requests</small>
                                    </label>
                                </div>

                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox"
                                           name="push_leave_status" value="1"
                                           {{ old('push_leave_status', $user->notification_preferences['push_leave_status'] ?? true) ? 'checked' : '' }}>
                                    <label class="form-check-label">
                                        <strong>Leave Status Updates</strong>
                                        <small class="d-block text-muted">Show in-app notifications for status changes</small>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="d-flex justify-content-between">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Save Preferences
                            </button>
                            <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Back to Dashboard
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Test Notification Section -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0">Test Notifications</h5>
                </div>
                <div class="card-body">
                    <p class="text-muted">Send yourself a test notification to verify your settings.</p>
                    <form method="POST" action="{{ route('test-notification') }}">
                        @csrf
                        <div class="btn-group" role="group">
                            <button type="submit" name="type" value="email" class="btn btn-outline-primary">
                                <i class="fas fa-envelope"></i> Test Email
                            </button>
                            <button type="submit" name="type" value="push" class="btn btn-outline-success">
                                <i class="fas fa-bell"></i> Test In-App
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
