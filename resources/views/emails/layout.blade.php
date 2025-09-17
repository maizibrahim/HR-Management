<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }}</title>
    <style>
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            padding: 20px;
        }
        .email-content {
            background-color: white;
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #e9ecef;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .logo {
            max-width: 200px;
            height: auto;
        }
        .button {
            display: inline-block;
            padding: 12px 24px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            margin: 20px 0;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e9ecef;
            color: #6c757d;
            font-size: 14px;
        }
        .status-approved {
            color: #10B981;
            font-weight: bold;
        }
        .status-rejected {
            color: #EF4444;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="email-content">
            <div class="header">
                <img src="{{ asset('images/company-logo.png') }}" alt="{{ config('app.name') }}" class="logo">
                <h2>{{ config('app.name') }} - Employee Portal</h2>
            </div>

            @yield('content')

            <div class="footer">
                <p>This is an automated message from {{ config('app.name') }}.</p>
                <p>If you have any questions, please contact HR at hr@company.com</p>
            </div>
        </div>
    </div>
</body>
</html>

<?php
