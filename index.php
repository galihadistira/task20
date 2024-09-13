<?php

$conn = new mysqli('localhost', 'root', '', 'tugas19');

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}


$categoryId = isset($_GET['category_id']) ? (int)$_GET['category_id'] : 0;


$articlesPerPage = 5;


$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;


$offset = ($page - 1) * $articlesPerPage;


if ($categoryId > 0) {
    
    $totalArticlesQuery = "SELECT COUNT(*) AS total FROM artikel WHERE category_id = $categoryId";
    $totalArticlesResult = $conn->query($totalArticlesQuery);
    $totalArticlesRow = $totalArticlesResult->fetch_assoc();
    $totalArticles = $totalArticlesRow['total'];

    
    $totalPages = ceil($totalArticles / $articlesPerPage);

    
    $articlesQuery = "SELECT * FROM artikel WHERE category_id = $categoryId LIMIT $articlesPerPage OFFSET $offset";
} else {
    
    $searchKeyword = isset($_GET['search']) ? $_GET['search'] : '';

    
    $totalArticlesQuery = "SELECT COUNT(*) AS total FROM artikel WHERE title LIKE '%$searchKeyword%'";
    $totalArticlesResult = $conn->query($totalArticlesQuery);
    $totalArticlesRow = $totalArticlesResult->fetch_assoc();
    $totalArticles = $totalArticlesRow['total'];

    
    $totalPages = ceil($totalArticles / $articlesPerPage);

    
    $articlesQuery = "SELECT * FROM artikel WHERE title LIKE '%$searchKeyword%' LIMIT $articlesPerPage OFFSET $offset";
}

$articlesResult = $conn->query($articlesQuery);

if (!$articlesResult) {
    die("Query Error: " . $conn->error);
}

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>List of Articles</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">

        <nav class="navbar">
            <ul>
                <?php
                $categoriesQuery = "SELECT * FROM categories";
                $categoriesResult = $conn->query($categoriesQuery);
                while($category = $categoriesResult->fetch_assoc()): ?>
                    <li><a href="index.php?category_id=<?= $category['id'] ?>"><?= $category['name'] ?></a></li>
                <?php endwhile; ?>
            </ul>
        </nav>

        <form method="GET" action="index.php" class="search-form">
            <input type="text" name="search" placeholder="Cari artikel..." value="<?= htmlspecialchars($searchKeyword ?? '') ?>" class="search-input">
            <button type="submit" class="search-button">Cari</button>
        </form>

        <div class="artikel-list">
            <?php while($artikel = $articlesResult->fetch_assoc()): ?>
                <div class="artikel-item">
                    <h2><a href="artikel.php?id=<?= $artikel['id'] ?>"><?= $artikel['title'] ?></a></h2>
                    <p><?= substr($artikel['content'], 0, 100) ?>...</p>
                </div>
            <?php endwhile; ?>
        </div>

        <!-- Pagination Links -->
        <div class="pagination">
            <?php if ($page > 1): ?>
                <a href="?category_id=<?= $categoryId ?>&page=<?= $page - 1 ?>&search=<?= htmlspecialchars($searchKeyword ?? '') ?>">Previous</a>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <a href="?category_id=<?= $categoryId ?>&page=<?= $i ?>&search=<?= htmlspecialchars($searchKeyword ?? '') ?>" class="<?= $i === $page ? 'active' : '' ?>"><?= $i ?></a>
            <?php endfor; ?>

            <?php if ($page < $totalPages): ?>
                <a href="?category_id=<?= $categoryId ?>&page=<?= $page + 1 ?>&search=<?= htmlspecialchars($searchKeyword ?? '') ?>">Next</a>
            <?php endif; ?>
        </div>

    </div>
</body>
</html>
