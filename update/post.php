<?php
$posts = [];
if (($handle = fopen("blog_data.csv", "r")) !== FALSE) {
    $headers = fgetcsv($handle);
    while (($data = fgetcsv($handle)) !== FALSE) {
        $posts[] = array_combine($headers, $data);
    }
    fclose($handle);
}

$slug = $_GET['slug'] ?? '';
$foundPost = null;

foreach ($posts as $post) {
    $postSlug = strtolower(preg_replace('/[^\w]+/', '-', $post['title']));
    if ($postSlug === $slug) {
        $foundPost = $post;
        break;
    }
}

if (!$foundPost) {
    header("HTTP/1.0 404 Not Found");
    die('Post not found');
}
?>
<!DOCTYPE html>
<html>
<head>
    <title><?= htmlspecialchars($foundPost['title']) ?></title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="single-post">
        <h1><?= htmlspecialchars($foundPost['title']) ?></h1>
        <div class="post-meta">
            By <?= htmlspecialchars($foundPost['author']) ?> 
            on <?= date('F j, Y', strtotime($foundPost['date'])) ?>
        </div>
        <img src="<?= htmlspecialchars($foundPost['image']) ?>" class="featured-image">
        <div class="post-content">
            <?= nl2br(htmlspecialchars($foundPost['description'])) ?>
        </div>
        <a href="index.php">← Back to Blog</a>
    </div>
</body>
</html>
