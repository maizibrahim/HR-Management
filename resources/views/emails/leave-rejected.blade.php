@extends('emails.layout')

@section('content')
<div style="text-align: center; margin-bottom: 30px;">
    <div style="background-color: #EF4444; color: white; padding: 15px; border-radius: 8px; display: inline-block;">
        <h2 style="margin: 0;">✗ Leave Request Not Approved</h2>
    </div>
</div>

<p><strong>Hello {{ $leaveRequest->user->name }},</strong></p>

<p>We regret to inform you that your leave request has been <span class="status-rejected">not approved</span>.</p>

<div style="background-color: #f8f9fa; padding: 20px; border-radius: 8px; margin: 20px 0;">
    <h3 style="margin-top: 0;">Leave Request Details:</h3>
    <ul style="list-style: none; padding: 0;">
        <li><strong>Leave Type:</strong> {{ $leaveRequest->leaveType->name }}</li>
        <li><strong>Requested Dates:</strong> {{ $leaveRequest->start_date->format('F j, Y') }} to {{ $leaveRequest->end_date->format('F j, Y') }}</li>
        <li><strong>Total Days:</strong> {{ $leaveRequest->days_requested }} day{{ $leaveRequest->days_requested > 1 ? 's' : '' }}</li>
    </ul>
</div>

@if($leaveRequest->review_comments)
<div style="background-color: #fff3cd; border: 1px solid #ffeaa7; padding: 15px; border-radius: 8px; margin: 20px 0;">
    <h4 style="margin-top: 0; color: #856404;">Supervisor Comments:</h4>
    <p style="margin-bottom: 0;">{{ $leaveRequest->review_comments }}</p>
</div>
@endif

<p>If you have any questions about this decision, please speak with your supervisor or contact HR.</p>

<div style="text-align: center; margin: 30px 0;">
    <a href="{{ url('/leave-requests') }}" class="button">
        View Your Leave Requests
    </a>
</div>
@endsection
