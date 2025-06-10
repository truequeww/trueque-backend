<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset</title>
    <style>
        /* General styles for email */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
            color: #333;
        }

        .email-wrapper {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .email-header {
            background-color: #347d27;
            color: white;
            text-align: center;
            padding: 20px 0;
            font-size: 24px;
            font-weight: bold;
        }

        .email-body {
            padding: 20px;
            text-align: center;
        }

        .verification-code {
            display: inline-block;
            font-size: 28px;
            font-weight: bold;
            color: #333;
            padding: 15px 25px;
            background-color: #f4f4f9;
            border: 2px solid #ddd;
            border-radius: 6px;
        }

        .email-footer {
            text-align: center;
            padding: 20px;
            font-size: 14px;
            color: #777;
            background-color: #f9f9f9;
        }

        .email-footer a {
            color: #347d27;
            text-decoration: none;
        }

        @media only screen and (max-width: 600px) {
            .email-wrapper {
                width: 100%;
                padding: 15px;
            }
        }
    </style>
</head>
<body>
    <div class="email-wrapper">
        <!-- Email Header -->
        <div class="email-header">
            Password Reset
        </div>

        <!-- Email Body -->
        <div class="email-body">
            <p>Hi,</p>
            <p>Thank you for using Trueque! To reset your password, please use the following verification code:</p>
            <div class="verification-code">
                {{ $param }}
            </div>
            <p>If you didn't ask for a password reset, please ignore this email.</p>
        </div>

        <!-- Email Footer -->
        <div class="email-footer">
            <p>Best regards,<br> The Trueque Team</p>
            <p>Visit our website: <a href="https://www.trueque.art/#/home" target="_blank">trueque.art</a></p>
        </div>
    </div>
</body>
</html>
