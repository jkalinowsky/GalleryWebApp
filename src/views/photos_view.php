<?php
require_once __DIR__ . '/../ViewHelper.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Galeria Zdjęć</title>
    <link rel="stylesheet" href="static/css/styles.css">
</head>
<body>

<section class="section">
    <?php ViewHelper::renderLoginForm(); ?>
</section>



<form method="post" action="/user_gallery/add" class="form">
    <table class="table">
        <thead>
        <tr>
            <th>Tytuł i autor</th>
            <th>Zdjęcie</th>
            <th>Operacje</th>
        </tr>
        </thead>
        <tbody>
        <?php ViewHelper::renderPhotoTable($model); ?>
        </tbody>
    </table>
    <div class="pagination">
        <?php ViewHelper::renderPagination($model['currentPage'], $model['totalPages']); ?>
    </div>
</form>

<div class="bottom-links">
    <a href="edit" class="link">Dodaj Zdjęcie</a>
    <a href="user_gallery" class="link">Zapisane zdjęcia</a>
    <a href="search" class="link">Wyszukaj zdjęcie</a>
</div>

</body>
</html>