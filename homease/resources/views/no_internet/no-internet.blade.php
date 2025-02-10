<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>No Internet Connection</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
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

        .icon {
            font-size: 50px;
            color: #e74c3c;
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
    <div class="container">
        <i class="fas fa-wifi-slash icon"></i> <!-- No Internet Icon -->
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
