<!DOCTYPE html>
<html>

<head>
    <title>Rejestracja</title>
    <link rel="stylesheet" href="static/css/styles.css">
</head>

<body>

<form method="post" enctype="multipart/form-data">
    <?php if (isset($_GET['error'])): ?>
        <div style="color: red;"><?= htmlspecialchars($_GET['error']) ?></div>
    <?php endif; ?>
    <label>
        <span>Login:</span>
        <input type="text" name="login" value="<?= $user['login'] ?? '' ?>" required />
        <span>email:</span>
        <input type="email" name="email" value="<?= $user['email'] ?? '' ?>" required />
        <span>Hasło:</span>
        <input type="password" name="password" value="<?= $user['password'] ?? '' ?>" required />
        <span>Powtóz hasło:</span>
        <input type="password" name="password2" required />
    </label>
    <div>
        <a href="photos" class="cancel">Anuluj</a>
        <input type="submit" value="Zarejestruj" />
    </div>
</form>

</body>
</html>
