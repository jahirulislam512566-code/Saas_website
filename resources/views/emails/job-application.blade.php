{{-- resources/views/emails/job-application.blade.php --}}
<!DOCTYPE html>
<html>
<head>
    <title>New Job Application</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f8f7ff; padding: 20px; }
        .container { max-width: 600px; margin: 0 auto; background: white; border-radius: 16px; padding: 30px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
        .header { background: #6c5ce7; color: white; padding: 20px; border-radius: 12px; text-align: center; margin-bottom: 20px; }
        .field { margin-bottom: 15px; }
        .label { font-weight: bold; color: #0a0a0a; display: block; margin-bottom: 5px; }
        .value { background: #f3f4f6; padding: 10px; border-radius: 8px; color: #4a4a4a; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>New Job Application</h1>
            <p>Position: {{ $data['position'] }}</p>
        </div>

        <div class="field">
            <span class="label">Name</span>
            <div class="value">{{ $data['first_name'] }} {{ $data['last_name'] }}</div>
        </div>

        <div class="field">
            <span class="label">Email</span>
            <div class="value">{{ $data['email'] }}</div>
        </div>

        @if(isset($data['phone']))
        <div class="field">
            <span class="label">Phone</span>
            <div class="value">{{ $data['phone'] }}</div>
        </div>
        @endif

        <div class="field">
            <span class="label">Position Applied For</span>
            <div class="value">{{ $data['position'] }}</div>
        </div>

        @if(isset($data['message']))
        <div class="field">
            <span class="label">Message</span>
            <div class="value">{{ $data['message'] }}</div>
        </div>
        @endif

        <div class="field">
            <span class="label">Resume</span>
            <div class="value">Attached to this email</div>
        </div>
    </div>
</body>
</html>