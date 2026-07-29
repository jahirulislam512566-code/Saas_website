{{-- resources/views/emails/contact.blade.php --}}
<!DOCTYPE html>
<html>
<head>
    <title>New Contact Form Submission</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #6c5ce7; color: white; padding: 20px; text-align: center; }
        .content { padding: 20px; background: #f8f7ff; }
        .field { margin-bottom: 15px; }
        .label { font-weight: bold; display: block; margin-bottom: 5px; }
        .value { background: white; padding: 10px; border-radius: 5px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>New Contact Form Submission</h1>
        </div>
        <div class="content">
            <div class="field">
                <span class="label">Name:</span>
                <div class="value">{{ $data['name'] }}</div>
            </div>
            <div class="field">
                <span class="label">Email:</span>
                <div class="value">{{ $data['email'] }}</div>
            </div>
            @if(isset($data['phone']))
            <div class="field">
                <span class="label">Phone:</span>
                <div class="value">{{ $data['phone'] }}</div>
            </div>
            @endif
            <div class="field">
                <span class="label">Subject:</span>
                <div class="value">{{ $data['subject'] }}</div>
            </div>
            <div class="field">
                <span class="label">Message:</span>
                <div class="value">{{ $data['message'] }}</div>
            </div>
        </div>
    </div>
</body>
</html>