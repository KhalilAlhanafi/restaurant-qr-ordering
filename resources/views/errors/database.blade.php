<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>System Maintenance</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: #f5f5f5;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 20px;
        }
        .container {
            background: white;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            text-align: center;
            max-width: 500px;
        }
        .icon {
            font-size: 64px;
            margin-bottom: 20px;
        }
        h1 {
            color: #333;
            font-size: 24px;
            margin-bottom: 15px;
        }
        p {
            color: #666;
            font-size: 16px;
            line-height: 1.6;
            margin-bottom: 20px;
        }
        .retry-button {
            background: #007bff;
            color: white;
            padding: 12px 30px;
            border-radius: 6px;
            text-decoration: none;
            display: inline-block;
            font-weight: 500;
        }
        .retry-button:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="icon">⏳</div>
        <h1>System Temporarily Unavailable</h1>
        <p>
            The database is currently unavailable. This usually happens when the 
            system is starting up or during brief maintenance.
        </p>
        <p style="font-size: 14px; color: #999;">
            Retrying automatically in <span id="countdown">5</span> seconds...
        </p>
        <a href="javascript:location.reload()" class="retry-button">Retry Now</a>
    </div>

    <script>
        let seconds = 5;
        const countdown = document.getElementById('countdown');
        
        setInterval(function() {
            seconds--;
            if (seconds <= 0) {
                location.reload();
            } else {
                countdown.textContent = seconds;
            }
        }, 1000);
    </script>
</body>
</html>
