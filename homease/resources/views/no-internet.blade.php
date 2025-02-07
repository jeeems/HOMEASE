<!-- resources/views/no-internet.blade.php -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>No Internet Connection</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f7f7;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .container {
            text-align: center;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #e74c3c;
        }

        p {
            font-size: 16px;
            color: #555;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>No Internet Connection</h1>
        <p>It seems like you're not connected to the internet. Please check your connection and try again.</p>
    </div>

    <script>
        if (!navigator.onLine) {
            window.location.href = '/no-internet'; // Redirect to the No Internet page
        }

        // Optional: Detect when the connection is restored
        window.addEventListener('online', function() {
            window.location.href = '/'; // Redirect to the homepage (or any other page you prefer)
        });
    </script>

</body>

</html>
