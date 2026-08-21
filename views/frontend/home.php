<?php
// Get settings from database (will implement later)
$siteName = 'GenzNewz';
$tagline = 'Your News. Your Voice.';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title ?? 'GenzNewz — Latest News & ePaper'; ?></title>
    <link rel="stylesheet" href="/assets/css/frontend/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Bengali:wght@400;500;700&display=swap" rel="stylesheet">
</head>
<body>
    <!-- Header -->
    <header class="site-header">
        <div class="container">
            <div class="header-top">
                <div class="logo">
                    <h1><?php echo $siteName; ?></h1>
                    <span class="tagline"><?php echo $tagline; ?></span>
                </div>
                <div class="header-actions">
                    <span class="current-date"><?php echo date('l, d F Y'); ?></span>
                    <a href="/login" class="btn-login">Login</a>
                </div>
            </div>
            <nav class="main-nav">
                <ul>
                    <li><a href="/">Home</a></li>
                    <li><a href="/archive">ePaper Archive</a></li>
                    <li><a href="/category/kolkata">Kolkata</a></li>
                    <li><a href="/category/state">State</a></li>
                    <li><a href="/category/national">India</a></li>
                    <li><a href="/category/international">World</a></li>
                    <li><a href="/category/sports">Sports</a></li>
                    <li><a href="/category/entertainment">Entertainment</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <!-- Main Content -->
    <main>
        <div class="container">
            <div class="hero-section">
                <h2>Welcome to <?php echo $siteName; ?></h2>
                <p class="hero-text"><?php echo $tagline; ?></p>
                <div class="hero-actions">
                    <a href="/archive" class="btn-primary">Read ePaper</a>
                    <a href="/archive" class="btn-secondary">Browse Archive</a>
                </div>
            </div>

            <div class="features-grid">
                <div class="feature-card">
                    <h3>📰 Latest News</h3>
                    <p>Stay updated with the latest news from around the world.</p>
                </div>
                <div class="feature-card">
                    <h3>📄 ePaper</h3>
                    <p>Read the digital edition of GenzNewz newspaper online.</p>
                </div>
                <div class="feature-card">
                    <h3>📚 Archive</h3>
                    <p>Access past editions and articles from our archive.</p>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="site-footer">
        <div class="container">
            <p>&copy; <?php echo date('Y'); ?> <?php echo $siteName; ?>. All Rights Reserved.</p>
        </div>
    </footer>

    <script src="/assets/js/frontend/main.js"></script>
</body>
</html>