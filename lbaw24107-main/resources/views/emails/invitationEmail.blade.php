<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Project Invitation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            background-color: #f9f9f9;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            color: #007bff;
        }
        .content {
            margin-bottom: 20px;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
        .btn:hover {
            background-color: #0056b3;
        }
        .footer {
            text-align: center;
            font-size: 12px;
            color: #aaa;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>You're Invited to Join a Project</h1>
        </div>
        <div class="content">
            <p>Hi {{ $mailData['recipientName'] }},</p>
            <p>You have been invited to join the project <strong>{{ $mailData['projectName'] }}</strong> by <strong>{{ $mailData['senderName'] }}</strong>.</p>
            <p>{{ $mailData['senderName'] }} thinks you'll be a great addition to the project and would love to have you onboard!</p>
            <p>To accept or decline the invitation, simply click one of the buttons below:</p>
            <form action="{{ route('notifications.respond') }}" method="POST" class="flex space-x-4">
                @csrf
                <input type="hidden" name="notification_id" value="{{ $mailData['notificationId'] }}">
                
                <button type="submit" name="response" value="accept" 
                        class="bg-green-500 hover:bg-green-600 text-white font-semibold py-2 px-4 rounded">
                    Accept
                </button>

                <button type="submit" name="response" value="decline" 
                        class="bg-red-500 hover:bg-red-600 text-white font-semibold py-2 px-4 rounded">
                    Decline
                </button>
            </form>
        </div>
        <div class="footer">
            <p>If you did not expect to receive this invitation, you can safely ignore this email.</p>
        </div>
    </div>
</body>
</html>
