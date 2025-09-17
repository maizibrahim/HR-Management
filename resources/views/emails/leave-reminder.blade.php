@extends('emails.layout')

@section('content')
<div style="text-align: center; margin-bottom: 30px;">
    <div style="background-color: #ffc107; color: #212529; padding: 15px; border-radius: 8px; display: inline-block;">
        <h2 style="margin: 0;">⏰ Leave Reminder</h2>
    </div>
</div>

<p><strong>Hello {{ $user->name }},</strong></p>

<p>This is a friendly reminder about your upcoming approved leave:</p>

<div style="background-color: #fff3cd; border: 1px solid #ffeaa7; padding: 20px; border-radius: 8px; margin: 20px 0;">
    <h3 style="margin-top: 0; color: #856404;">Leave Details:</h3>
    <ul style="list-style: none; padding: 0; color: #856404;">
        <li><strong>Leave Type:</strong> {{ $leaveRequest->leaveType->name }}</li>
        <li><strong>Start Date:</strong> {{ $leaveRequest->start_date->format('F j, Y') }}</li>
        <li><strong>End Date:</strong> {{ $leaveRequest->end_date->format('F j, Y') }}</li>
        <li><strong>Duration:</strong> {{ $leaveRequest->days_requested }} day{{ $leaveRequest->days_requested > 1 ? 's' : '' }}</li>
    </ul>
</div>

<div style="background-color: #d4edda; border: 1px solid #c3e6cb; padding: 15px; border-radius: 8px; margin: 20px 0;">
    <h4 style="margin-top: 0; color: #155724;">Before you go:</h4>
    <ul style="color: #155724;">
        <li>Complete pending tasks and handovers</li>
        <li>Set up out-of-office email responses</li>
        <li>Brief your team on urgent matters</li>
        <li>Update your calendar status</li>
    </ul>
</div>

<div style="text-align: center; margin: 30px 0;">
    <a href="{{ url('/leave-requests/' . $leaveRequest->id) }}" class="button" style="background-color: #ffc107; color: #212529;">
        View Leave Details
    </a>
</div>

<p>Have a great time off!</p>
@endsection
