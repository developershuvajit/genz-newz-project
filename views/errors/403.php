<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 - Access Denied | GenzNewz</title>
    <link rel="stylesheet" href="/assets/css/frontend/style.css">
    <style>
        .error-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            text-align: center;
            padding: 2rem;
        }
        .error-container h1 {
            font-size: 6rem;
            color: var(--primary-green);
            margin: 0;
        }
        .error-container h2 {
            font-size: 2rem;
            color: var(--text-dark);
            margin: 1rem 0;
        }
        .error-container p {
            color: #6B7280;
            margin-bottom: 2rem;
        }
        .error-container .btn {
            display: inline-block;
            padding: 0.75rem 2rem;
            background: var(--primary-green);
            color: var(--white);
            text-decoration: none;
            border-radius: 0.375rem;
            transition: background 0.3s;
        }
        .error-container .btn:hover {
            background: var(--dark-green);
        }
    </style>
</head>
<body>
    <div class="error-container">
        <h1>403</h1>
        <h2>Access Denied</h2>
        <p>You do not have permission to access this page.</p>
        <a href="/" class="btn">Return to Homepage</a>
    </div>
</body>
</html>