<!DOCTYPE html>
<html>
<head>
    <title>500 Internal Server Error</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 40px;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            text-align: center;
        }
        h1 {
            font-size: 2.5rem;
            margin-bottom: 0.5rem;
        }
        p {
            font-size: 1rem;
            line-height: 1.5;
            margin-bottom: 1rem;
        }
        .home-link {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
        .home-link:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>500 Internal Server Error</h1>
    <p>Sorry, something went wrong on our end.</p>
    <p>Please try again later or return to the homepage.</p>
    <a href="<?= BASE_URL ?>/Public/index.php" class="home-link">Go to Home</a>
</div>
</body>
</html>
