<?php
header('Content-Type: application/json');

// Load the CSV data
$posts = [];
if (($handle = fopen("blog_data.csv", "r")) !== FALSE) {
    $headers = fgetcsv($handle);
    while (($data = fgetcsv($handle)) !== FALSE) {
        $posts[] = array_combine($headers, $data);
    }
    fclose($handle);
}

// Output as JSON
echo json_encode($posts);
?>
