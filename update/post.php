<?php
// Set the content type to HTML
header('Content-Type: text/html; charset=utf-8');

// Load the CSV data
$posts = [];
if (($handle = fopen("blog_data.csv", "r")) !== FALSE) {
    $headers = fgetcsv($handle);
    while (($data = fgetcsv($handle)) !== FALSE) {
        $posts[] = array_combine($headers, $data);
    }
    fclose($handle);
}

// Get the requested post
$slug = $_GET['slug'] ?? '';
$date = $_GET['date'] ?? '';
$foundPost = null;

foreach ($posts as $post) {
    $postSlug = strtolower($post['title']);
    $postSlug = preg_replace('/[^\w\s]/', '', $postSlug);
    $postSlug = preg_replace('/\s+/', '-', $postSlug);
    $postSlug = substr($postSlug, 0, 50);
    
    if ($postSlug === $slug) {
        $foundPost = $post;
        break;
    }
}

if (!$foundPost) {
    header("HTTP/1.0 404 Not Found");
    die('Post not found');
}

// Extract date parts for the title if needed
$dateParts = explode('/', $date);
$year = $dateParts[0] ?? date('Y');
$month = $dateParts[1] ?? date('m');
$day = $dateParts[2] ?? date('d');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($foundPost['title']) ?> | My Blog</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <!-- Header -->
    <header>
        <div class="container">
            <nav class="main-nav">
                <div class="logo">My Blog</div>
                <div class="menu-toggle">
                    <i class="fas fa-bars"></i>
                </div>
                <ul id="nav-menu">
                    <li><a href="index.html">Home</a></li>
                    <li><a href="#">About</a></li>
                    <li><a href="#">Categories</a></li>
                    <li><a href="#">Contact</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <!-- Main Content -->
    <main class="container">
        <div class="single-post">
            <div class="post-header">
                <h1 class="post-title"><?= htmlspecialchars($foundPost['title']) ?></h1>
                <div class="post-meta">
                    <span class="author">By <?= htmlspecialchars($foundPost['author']) ?></span>
                    <span class="date"><?= date('F j, Y', strtotime($foundPost['date'])) ?></span>
                </div>
            </div>
            <div class="featured-image">
                <img src="<?= htmlspecialchars($foundPost['image']) ?>" alt="<?= htmlspecialchars($foundPost['title']) ?>">
            </div>
            <div class="post-content">
                <?= nl2br(htmlspecialchars($foundPost['description'])) ?>
            </div>
            <a href="index.html" class="back-link">← Back to Blog</a>
        </div>
    </main>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3>About Us</h3>
                    <p>Welcome to our blog where we share interesting articles on various topics.</p>
                    <div class="social-links">
                        <a href="#"><i class="fab fa-facebook"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-linkedin"></i></a>
                    </div>
                </div>
                <div class="footer-section">
                    <h3>Quick Links</h3>
                    <ul>
                        <li><a href="index.html">Home</a></li>
                        <li><a href="#">About</a></li>
                        <li><a href="#">Categories</a></li>
                        <li><a href="#">Contact</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h3>Contact Info</h3>
                    <ul>
                        <li><i class="fas fa-map-marker-alt"></i> 123 Blog Street, City</li>
                        <li><i class="fas fa-phone"></i> (123) 456-7890</li>
                        <li><i class="fas fa-envelope"></i> info@myblog.com</li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; <?= date('Y') ?> My Blog. All Rights Reserved.</p>
            </div>
        </div>
    </footer>

    <script>
        // Mobile menu toggle
        document.querySelector('.menu-toggle')?.addEventListener('click', function() {
            document.getElementById('nav-menu').classList.toggle('show');
        });
    </script>
</body>
</html>
