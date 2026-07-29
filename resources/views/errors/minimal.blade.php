{{-- resources/views/errors/minimal.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Error - AgencyPro')</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            background: #f8f7ff;
            color: #0a0a0a;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
            padding: 20px;
            text-align: center;
        }
        .error-container {
            max-width: 500px;
            width: 100%;
        }
        .error-code {
            font-size: 72px;
            font-weight: 800;
            color: #6c5ce7;
            line-height: 1;
        }
        .error-title {
            font-size: 24px;
            font-weight: 600;
            margin: 16px 0 8px;
        }
        .error-message {
            color: #4a4a4a;
            margin-bottom: 24px;
        }
        .btn {
            display: inline-block;
            padding: 10px 24px;
            background: #6c5ce7;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 500;
            transition: background 0.3s;
        }
        .btn:hover {
            background: #5a4bd1;
        }
        .btn-secondary {
            background: white;
            color: #0a0a0a;
            border: 2px solid #e5e7eb;
        }
        .btn-secondary:hover {
            border-color: #6c5ce7;
            color: #6c5ce7;
        }
        .links {
            margin-top: 16px;
            display: flex;
            gap: 12px;
            justify-content: center;
            flex-wrap: wrap;
        }
        .footer {
            margin-top: 32px;
            font-size: 14px;
            color: #9ca3af;
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="error-code">@yield('code', '500')</div>
        <h1 class="error-title">@yield('title', 'Something went wrong')</h1>
        <p class="error-message">@yield('message', 'An error occurred. Please try again later.')</p>
        <div class="links">
            <a href="{{ url('/') }}" class="btn">Go Home</a>
            <a href="{{ url()->previous() }}" class="btn btn-secondary">Go Back</a>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} AgencyPro. All rights reserved.
        </div>
    </div>
</body>
</html>