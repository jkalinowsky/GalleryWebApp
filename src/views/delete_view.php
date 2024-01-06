<!DOCTYPE html>
<html>
<head>
    <title>Usuwanie produktu</title>
    <link rel="stylesheet" href="static/css/styles.css"/>
</head>
<body>

<form method="post">
    Czy usunąć produkt: <?= $photo['name'] ?>?

    <input type="hidden" name="id" value="<?= $photo['_id'] ?>">

    <div>
        <a href="photos" class="cancel">Anuluj</a>
        <input type="submit" value="Potwierdź"/>
    </div>
</form>

</body>
</html>
