<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Set Your Password</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #f4f6f8;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            color: #333333;
        }
        .email-wrapper {
            width: 100%;
            padding: 24px 0;
        }
        .email-content {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 6px;
            overflow: hidden;
            box-shadow: 0 2px 6px rgba(0,0,0,0.05);
        }
        .email-header {
            background-color: #2563eb;
            padding: 20px;
            text-align: center;
            color: #ffffff;
            font-size: 20px;
            font-weight: 600;
        }
        .email-body {
            padding: 30px;
            line-height: 1.6;
        }
        .email-body h1 {
            font-size: 22px;
            margin-bottom: 16px;
            color: #111827;
        }
        .email-body p {
            font-size: 15px;
            margin-bottom: 16px;
        }
        .cta-wrapper {
            text-align: center;
            margin: 32px 0;
        }
        .cta-button {
            display: inline-block;
            padding: 14px 28px;
            background-color: #2563eb;
            color: #ffffff !important;
            text-decoration: none;
            font-size: 15px;
            font-weight: 600;
            border-radius: 4px;
        }
        .cta-button:hover {
            background-color: #1e40af;
        }
        .email-footer {
            background-color: #f9fafb;
            padding: 20px 30px;
            font-size: 12px;
            color: #6b7280;
        }
        .reset-link {
            word-break: break-all;
            color: #2563eb;
        }
    </style>
</head>
<body>

<div class="email-wrapper">
    <div class="email-content">

        <div class="email-header">
            {{ config('app.name') }}
        </div>

        <div class="email-body">
            <h1>Set your password</h1>

            <p>
                You are receiving this email because an account was created for you on
                <strong>{{ config('app.name') }}</strong>.
            </p>

            <p>
                Click the button below to set your password and activate your account.
            </p>

            <div class="cta-wrapper">
                <a href="{{ $resetUrl }}" class="cta-button">
                    Set Password
                </a>
            </div>

            <p>
                This password setup link will expire in <strong>60 minutes</strong>.
                If you did not request this, you can safely ignore this email.
            </p>

            <p>
                If the button above does not work, copy and paste the following link into your browser:
            </p>

            <p class="reset-link">
                {{ $resetUrl }}
            </p>
        </div>

        <div class="email-footer">
            <p>
                This is an automated message. Please do not reply to this email.
            </p>
            <p>
                © {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
            </p>
        </div>

    </div>
</div>

</body>
</html>
