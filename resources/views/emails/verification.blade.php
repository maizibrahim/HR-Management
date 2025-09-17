@extends('emails.layout')

@section('content')
<div style="text-align: center; margin-bottom: 30px;">
    <div style="background-color: #28a745; color: white; padding: 15px; border-radius: 8px; display: inline-block;">
        <h2 style="margin: 0;">Welcome to Employee Portal</h2>
    </div>
</div>

<p><strong>Hello {{ $user->name }},</strong></p>

<p>Welcome to our Employee Portal! To get started, please verify your email address by clicking the button below.</p>

<div style="background-color: #d1ecf1; border: 1px solid #bee5eb; padding: 15px; border-radius: 8px; margin: 20px 0;">
    <h4 style="margin-top: 0; color: #0c5460;">What you can do after verification:</h4>
    <ul style="color: #0c5460;">
        <li>Submit leave requests</li>
        <li>View your leave balances</li>
        <li>Track request status</li>
        <li>Review leave history</li>
    </ul>
</div>

<div style="text-align: center; margin: 30px 0;">
    <a href="{{ $verificationUrl }}" class="button" style="background-color: #28a745;">
        Verify Email Address
    </a>
</div>

<p>If the button doesn't work, copy and paste this link into your browser:</p>
<p style="word-break: break-all; background-color: #f8f9fa; padding: 10px; border-radius: 4px;">
    {{ $verificationUrl }}
</p>

<p>If you didn't create an account with us, please ignore this email.</p>
@endsection
