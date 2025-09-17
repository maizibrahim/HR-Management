@extends('emails.layout')

@section('content')
<div style="text-align: center; margin-bottom: 30px;">
    <div style="background-color: #10B981; color: white; padding: 15px; border-radius: 8px; display: inline-block;">
        <h2 style="margin: 0;">✓ Leave Request Approved</h2>
    </div>
</div>

<p><strong>Hello {{ $employeeName }},</strong></p>

<p>Great news! Your leave request has been <span class="status-approved">approved</span>.</p>

<div style="background-color: #f8f9fa; padding: 20px; border-radius: 8px; margin: 20px 0;">
    <h3 style="margin-top: 0;">Leave Details:</h3>
    <ul style="list-style: none; padding: 0;">
        <li><strong>Leave Type:</strong> {{ $leaveType }}</li>
        <li><strong>Start Date:</strong> {{ $startDate->format('F j, Y') }}</li>
        <li><strong>End Date:</strong> {{ $endDate->format('F j, Y') }}</li>
        <li><strong>Total Days:</strong> {{ $days }} day{{ $days > 1 ? 's' : '' }}</li>
    </ul>
</div>

<p>Please make sure to coordinate with your team regarding your absence and complete any handover tasks.</p>

<div style="text-align: center; margin: 30px 0;">
    <a href="{{ url('/leave-requests') }}" class="button" style="background-color: #10B981;">
        View Your Leave Requests
    </a>
</div>

<p>Enjoy your time off!</p>
@endsection
