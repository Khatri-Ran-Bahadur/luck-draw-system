<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Your Password - Lucky Draw System</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f8fafc;
        }

        .container {
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .header {
            text-align: center;
            padding: 40px 30px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .header .icon {
            width: 60px;
            height: 60px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
            font-size: 24px;
        }

        .header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 600;
        }

        .content {
            padding: 40px 30px;
        }

        .greeting {
            font-size: 20px;
            color: #2d3748;
            margin-bottom: 20px;
            font-weight: 600;
        }

        .message {
            color: #4a5568;
            font-size: 16px;
            margin-bottom: 30px;
            line-height: 1.7;
        }

        .button {
            display: inline-block;
            padding: 16px 32px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 16px;
            margin: 20px 0;
            transition: transform 0.2s ease;
        }

        .button:hover {
            transform: translateY(-2px);
        }

        .security-note {
            background-color: #fef5e7;
            border-left: 4px solid #f6ad55;
            padding: 16px;
            margin: 30px 0;
            border-radius: 4px;
        }

        .security-note h3 {
            color: #c05621;
            margin: 0 0 8px 0;
            font-size: 16px;
        }

        .security-note p {
            color: #744210;
            margin: 0;
            font-size: 14px;
        }

        .url-box {
            background: #f7fafc;
            padding: 15px;
            border-radius: 8px;
            font-family: monospace;
            word-break: break-all;
            border: 1px solid #e2e8f0;
            margin: 15px 0;
        }

        .footer {
            background-color: #f7fafc;
            text-align: center;
            padding: 30px;
            border-top: 1px solid #e2e8f0;
            color: #718096;
            font-size: 14px;
        }

        .footer .company {
            color: #2d3748;
            font-weight: 600;
            font-size: 16px;
            margin-bottom: 10px;
        }

        @media (max-width: 600px) {
            body {
                padding: 10px;
            }

            .header,
            .content,
            .footer {
                padding: 20px;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="icon">🔐</div>
            <h1>Password Reset Request</h1>
        </div>

        <!-- Content -->
        <div class="content">
            <div class="greeting">Hello <?= esc($name) ?>!</div>

            <div class="message">
                We received a request to reset your password for your Lucky Draw System account.
                If you made this request, click the button below to reset your password.
            </div>

            <div style="text-align: center;">
                <a href="<?= base_url('reset-password/' . $token) ?>" class="button">
                    Reset My Password
                </a>
            </div>

            <div class="security-note">
                <h3>🛡️ Security Information</h3>
                <p>
                    • This link will expire in <strong>1 hour</strong> for your security<br>
                    • If you didn't request this reset, you can safely ignore this email<br>
                    • Your password will remain unchanged until you create a new one
                </p>
            </div>

            <div style="color: #718096; font-size: 14px; margin-top: 30px;">
                <p><strong>Having trouble with the button?</strong> Copy and paste this link into your browser:</p>
                <div class="url-box">
                    <?= base_url('reset-password/' . $token) ?>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <div class="company">Lucky Draw System</div>
            <p>Making dreams come true, one draw at a time! 🎲</p>
            <p>This email was sent because you requested a password reset.</p>
            <p>&copy; <?= date('Y') ?> Lucky Draw System. All rights reserved.</p>
        </div>
    </div>
</body>

</html>