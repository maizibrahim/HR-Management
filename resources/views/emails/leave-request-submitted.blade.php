@extends('emails.layout')

@section('content')
<div style="text-align: center; margin-bottom: 30px;">
    <div style="background-color: #007bff; color: white; padding: 15px; border-radius: 8px; display: inline-block;">
        <h2 style="margin: 0;">📝 New Leave Request</h2>
    </div>
</div>

<p><strong>Hello {{ $supervisor->name }},</strong></p>

<p>A new leave request has been submitted and requires your review.</p>

<div style="background-color: #f8f9fa; padding: 20px; border-radius: 8px; margin: 20px 0;">
    <h3 style="margin-top: 0;">Request Details:</h3>
    <ul style="list-style: none; padding: 0;">
        <li><strong>Employee:</strong> {{ $leaveRequest->user->name }}</li>
        <li><strong>Leave Type:</strong> {{ $leaveRequest->leaveType->name }}</li>
        <li><strong>Start Date:</strong> {{ $leaveRequest->start_date->format('F j, Y') }}</li>
        <li><strong>End Date:</strong> {{ $leaveRequest->end_date->format('F j, Y') }}</li>
        <li><strong>Total Days:</strong> {{ $leaveRequest->days_requested }} day{{ $leaveRequest->days_requested > 1 ? 's' : '' }}</li>
        <li><strong>Submitted:</strong> {{ $leaveRequest->created_at->format('F j, Y \a\t g:i A') }}</li>
    </ul>
</div>

@if($leaveRequest->reason)
<div style="background-color: #e9ecef; padding: 15px; border-radius: 8px; margin: 20px 0;">
    <h4 style="margin-top: 0;">Reason for Leave:</h4>
    <p style="margin-bottom: 0;">{{ $leaveRequest->reason }}</p>
</div>
@endif

<p>Please review this request and approve or reject it through the employee portal.</p>

<div style="text-align: center; margin: 30px 0;">
    <a href="{{ url('/leave-approvals') }}" class="button">
        Review Leave Request
    </a>
</div>
@endsection
