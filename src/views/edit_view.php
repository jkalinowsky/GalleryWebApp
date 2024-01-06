<!DOCTYPE html>
<html>

<head>
    <title>Dodawanie Zdjęcia</title>
    <link rel="stylesheet" href="static/css/styles.css">
</head>

<body>

<form method="post" enctype="multipart/form-data">
    <?php if (isset($_GET['error'])): ?>
        <div style="color: red;"><?= htmlspecialchars($_GET['error']) ?></div>
    <?php endif; ?>
    <?php
    $photo['author'] = $_SESSION['user'] ?? '';
    ?>
    <label>
        <span>Tytuł:</span>
        <input type="text" name="name" value="<?= $photo['name'] ?? '' ?>" required />
        <span>Autor:</span>
        <input type="text" name="author" value="<?=$photo['author']?>" required />
        <span>Tekst znaku wodnego:</span>
        <input type="text" name="watermark_text" value="<?= $photo['watermark_text'] ?? '' ?>" required />
    </label>
    <label>
        <span>Zdjęcie:</span>
        <input type="file" name="photo" accept="image/*" />
    </label>
    <br>
    <?php if (isset($_SESSION['user'])): ?>
        <label>
            <input type="radio" name="privacy" value="public" checked>
            Publiczne
        </label>
        <label>
            <input type="radio" name="privacy" value="private">
            Prywatne
        </label>
    <?php else: ?>
        <input type="hidden" name="privacy" value="public">
    <?php endif; ?>
    <input type="hidden" name="id" value="<?= $photo['id'] ?? '' ?>">
    <div>
        <a href="photos" class="cancel">Anuluj</a>
        <input type="submit" value="Zapisz" />
    </div>
</form>

</body>
</html>
