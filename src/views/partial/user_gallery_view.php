<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zapisane zdjęcia</title>
    <link rel="stylesheet" href="static/css/styles.css">
</head>
<body>

<div id="user_gallery" class="container">
    <form method="post" action="/user_gallery/clear" class="form" data-role="gallery_form">
        <table class="table">
            <thead>
            <tr>
                <th>Tytuł i autor</th>
                <th>Zdjęcie</th>
                <th>Operacje</th>
            </tr>
            </thead>

            <tbody>
            <?php if (!empty($user_gallery)): ?>
                <?php foreach ($user_gallery as $id => $photo): ?>
                    <tr>
                        <td>
                            <?= $photo['name'] ?> <br>
                            ~ <?= $photo['author'] ?>
                        </td>
                        <td>
                            <a href="view?id=<?= $photo['_id'] ?>"><img src="<?= $photo['thumbnail'] ?>" alt="<?= $photo['name'] ?>" class="img-thumbnail" /></a>
                        </td>
                        <td>
                            <input type="checkbox" name="user_gallery[]" value="<?=$photo['_id']?>">
                        </td>
                    </tr>
                <?php endforeach ?>
            <?php else: ?>
                <tr>
                    <td colspan="3">Brak zdjęć w zapisanej galerii</td>
                </tr>
            <?php endif ?>
            </tbody>

            <tfoot>
            <tr>
                <td colspan="2">Łącznie pozycji: <?= count($user_gallery) ?></td>
                <td><input type="submit" value="Usuń wybrane" name="clear_user_gallery" class="button"/></td>
            </tr>
            </tfoot>
        </table>
    </form>
    <a href="photos" class="link">Wróć</a>
</div>

</body>