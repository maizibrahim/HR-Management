@extends('emails.layout')

@section('content')
<div style="text-align: center; margin-bottom: 30px;">
    <div style="background-color: #17a2b8; color: white; padding: 15px; border-radius: 8px; display: inline-block;">
        <h2 style="margin: 0;">🔄 Leave Balances Reset</h2>
    </div>
</div>

<p><strong>Hello {{ $user->name }},</strong></p>

<p>Your leave balances have been reset for the new period! Here are your updated balances:</p>

<div style="background-color: #f8f9fa; padding: 20px; border-radius: 8px; margin: 20px 0;">
    <h3 style="margin-top: 0;">Updated Leave Balances:</h3>
    @foreach($balances as $balance)
    <div style="display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid #dee2e6;">
        <span><strong>{{ $balance['leave_type'] }}:</strong></span>
        <span style="color: #10B981; font-weight: bold;">{{ $balance['days'] }} days</span>
    </div>
    @endforeach
</div>

<p>You can now submit new leave requests using your refreshed leave balance.</p>

<div style="text-align: center; margin: 30px 0;">
    <a href="{{ url('/leave-requests') }}" class="button" style="background-color: #17a2b8;">
        Submit New Leave Request
    </a>
</div>

<p>Plan your time off and maintain a healthy work-life balance!</p>
@endsection
