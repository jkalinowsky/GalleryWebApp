<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wyszukiwarka Zdjęć</title>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <link rel="stylesheet" href="static/css/styles.css">
</head>
<body>

<?php if (!isset($model['isAjax']) || !$model['isAjax']): ?>
    <h1>Wyszukiwarka Zdjęć</h1>
    <input type="text" id="searchInput" placeholder="Wpisz fragment tytułu zdjęcia">
<?php endif; ?>

<div id="results">
    <?php if (isset($model['photos'])): ?>
        <?php foreach ($model['photos'] as $photo): ?>
            <a href="view?id=<?= $photo['_id'] ?>"><img src="<?= $photo['thumbnail'] ?>" alt="<?= $photo['name'] ?>" /></a>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<script>
    $(document).ready(function() {
        var lastQuery = "";

        $("#searchInput").on("keyup", function() {
            var query = $(this).val();
            if (query !== lastQuery) {
                lastQuery = query;

                $.ajax({
                    url: "search",
                    method: "POST",
                    data: { query: query },
                    success: function(data) {
                        var photos = JSON.parse(data);
                        $("#results").empty();
                        for (var key in photos) {
                            if (photos.hasOwnProperty(key)) {
                                var photo = photos[key];
                                $("#results").append('<a href="view?id=' + photo._id.toString() + '"><img src="' +
                                    photo.thumbnail + '" alt="' + photo.name + '">');
                            }
                        }
                    }
                });
            }
        });
    });
</script>
</body>
</html>
