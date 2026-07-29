{{-- resources/views/emails/auth/welcome.blade.php --}}
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { text-align: center; padding: 20px 0; border-bottom: 2px solid #eee; }
        .logo { max-width: 200px; }
        .content { padding: 20px 0; }
        .button { display: inline-block; background: #4CAF50; color: white; padding: 12px 24px; text-decoration: none; border-radius: 4px; }
        .footer { text-align: center; padding: 20px 0; border-top: 2px solid #eee; color: #999; font-size: 12px; }
    </style>
</head>
<body>
    <div class="header">
        <img src="{{ $company_logo ?? asset('images/logo.png') }}" alt="{{ $company_name }}" class="logo">
    </div>

    <div class="content">
        <h1>Welcome to {{ $company_name }}!</h1>
        
        <p>Hello <strong>{{ $user->name }}</strong>,</p>
        
        <p>We're excited to have you on board! Your account has been successfully created.</p>
        
        @if($password)
            <p>Here are your login credentials:</p>
            <ul>
                <li><strong>Email:</strong> {{ $user->email }}</li>
                <li><strong>Password:</strong> {{ $password }}</li>
            </ul>
            <p><strong>Please change your password after your first login.</strong></p>
        @endif

        <p>You can access your account and start exploring our services:</p>

        <p style="text-align: center;">
            <a href="{{ $dashboard_url ?? url('/') }}" class="button">Go to Dashboard</a>
        </p>

        <p>If you have any questions, our support team is here to help.</p>
        
        <p>Best regards,<br>{{ $company_name }}</p>
    </div>

    <div class="footer">
        <p>&copy; {{ date('Y') }} {{ $company_name }}. All rights reserved.</p>
        <p>Contact: {{ $support_email ?? 'support@example.com' }}</p>
    </div>
</body>
</html>