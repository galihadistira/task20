<?php
$conn = new mysqli('localhost', 'root', '', 'tugas19');

$articleId = $_GET['id'];

$articleQuery = "SELECT artikel.*, categories.name AS category_name FROM artikel
                JOIN categories ON artikel.category_id = categories.id 
                WHERE artikel.id = $articleId";
$articleResult = $conn->query($articleQuery);
$article = $articleResult->fetch_assoc();
?>


<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?= $article['title'] ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="artikel-detail">
        <h1><?= $article['title'] ?></h1>
        <p><strong>Category:</strong> <?= $article['category_name'] ?></p>
        <div class="content"><?= $article['content'] ?></div>
        <div class="content">
    
</div>

    </div>
</body>
</html>



