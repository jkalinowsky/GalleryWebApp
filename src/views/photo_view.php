<!DOCTYPE html>
<html>
<head>
    <title>Zdjecie</title>
    <link rel="stylesheet" href="static/css/styles.css">
</head>
<body>

<h1>Tytuł: <?= $photo['name'] ?></h1>
<h2>Autor: <?= $photo['author'] ?></h2>
<img src="<?= $photo['watermark'] ?>" alt="<?= $photo['name'] ?>" />

<a href="photos" class="cancel">&laquo; Wróć</a>

</body>
</html>
