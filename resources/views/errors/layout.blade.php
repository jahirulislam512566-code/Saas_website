{{-- resources/views/errors/layout.blade.php --}}

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Error')</title>
    <meta name="description" content="Error page">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body {
            background: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .error-container {
            text-align: center;
            padding: 40px;
        }
        .error-code {
            font-size: 120px;
            font-weight: 700;
            color: #dc3545;
            line-height: 1;
        }
        .error-title {
            font-size: 32px;
            font-weight: 600;
            margin: 20px 0;
        }
        .error-message {
            font-size: 18px;
            color: #6c757d;
            margin-bottom: 30px;
        }
        .error-divider {
            width: 80px;
            height: 4px;
            background: #dc3545;
            margin: 20px auto;
            border-radius: 2px;
        }
        .btn-home {
            padding: 12px 40px;
            font-size: 16px;
            border-radius: 50px;
        }
        .error-icon {
            font-size: 80px;
            color: #dc3545;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="error-container">
            {{-- Error Content --}}
            @yield('content')
            
            {{-- Alternative: Use slot if defined --}}
            @if(isset($slot))
                {{ $slot }}
            @endif
            
            {{-- Footer --}}
            <div class="mt-5">
                <p class="text-muted small">
                    &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
                </p>
            </div>
        </div>
    </div>
</body>
</html>