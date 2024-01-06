<?php
require_once 'business.php';
include_once 'controller_utils.php';
require_once 'controllers_function.php';

function photos(&$model)
{
    $photos = get_photos();
    $model['photos'] = $photos;

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['logout'])) {
        return logout($model);
    }

    $currentPage = $_GET['page'] ?? 1;

    $photosPerPage = 3;
    $start = ($currentPage - 1) * $photosPerPage;
    $end = $start + $photosPerPage;

    $visiblePhotos = array_filter($photos, function ($photo) {
        return $photo['privacy'] !== 'private' || ($_SESSION['user'] ?? null) === $photo['author'];
    });

    $totalPages = ceil(count($visiblePhotos) / $photosPerPage);
    $currentPhotos = array_slice($visiblePhotos, $start, $photosPerPage);

    $model['currentPhotos'] = $currentPhotos;
    $model['totalPages'] = $totalPages;
    $model['currentPage'] = $currentPage;

    return 'photos_view';
}

function photo(&$model)
{
    if (!empty($_GET['id'])) {
        $id = $_GET['id'];

        if ($photo = get_photo($id)) {
            $model['photo'] = $photo;
            return 'photo_view';
        }
    }

    http_response_code(404);
    exit;
}

function edit(&$model)
{
    $photo = [
        'name' => null,
        'author' => null,
        'photo' => null,
        'watermark' => null,
        'thumbnail' => null,
        'watermark_text' => null,
        'privacy' => null,
    ];

    $uploadDir = 'images/';

    createDirectory($uploadDir);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (!empty($_POST['name']) && isset($_FILES['photo'])) {
            $id = $_POST['id'] ?? md5(uniqid('', true));

            $photo['name'] = $_POST['name'];
            $photo['author'] = $_POST['author'];
            $photo['privacy'] = $_POST['privacy'];
            $photo['watermark_text'] = $_POST['watermark_text'];
            $photo['photo'] = $_FILES['photo']['name'];

            $allowedFileTypes = ['image/jpeg', 'image/png'];
            $fileType = $_FILES['photo']['type'];
            $maxFileSize = 1024 * 1024; // 1 MB
            $fileSize = $_FILES['photo']['size'];

            if ($fileSize > $maxFileSize || $fileSize === 0) {
                $errorMessage = 'Plik przekracza maksymalny rozmiar (1MB).';
                return 'redirect:edit?error=' . urlencode($errorMessage);
            } elseif (!in_array($fileType, $allowedFileTypes)) {
                $errorMessage = 'Nieprawidłowy typ pliku. Proszę przesłać plik w formacie JPG lub PNG.';
                return 'redirect:edit?error=' . urlencode($errorMessage);
            }

            $uploadFile = $uploadDir . basename($_FILES['photo']['name']);

            if (move_uploaded_file($_FILES['photo']['tmp_name'], $uploadFile)) {
                $photo['photo'] = $uploadFile;
                $photo['thumbnail'] = generate_thumbnail($photo, $uploadDir);
                $photo['watermark'] = generate_watermark($photo, $uploadDir);
            }

            if (save_photo($id, $photo)) {
                return 'redirect:photos';
            }
        }
    } elseif (!empty($_GET['id'])) {
        $photo = get_photo($_GET['id']);
    }

    $model['photo'] = $photo;

    return 'edit_view';
}

function delete(&$model)
{
    if (!empty($_REQUEST['id'])) {
        $id = $_REQUEST['id'];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            delete_photo_files($id);
            delete_photo($id);
            return 'redirect:photos';

        } else {
            if ($photo = get_photo($id)) {
                $model['photo'] = $photo;
                return 'delete_view';
            }
        }
    }

    http_response_code(404);
    exit;
}

function register(&$model)
{
    $user = [
        'login' => null,
        'email' => null,
        'password' => null,
    ];
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $user['login'] = htmlspecialchars($_POST['login']);
        $user['email'] = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        $user['password'] = password_hash($_POST['password'], PASSWORD_DEFAULT);

        if ($_POST['password'] === $_POST['password2']) {
            if (save_user($user['login'], $user)) {
                return 'redirect:photos';
            } else {
                $errorMessage = 'Podany login jest już zajęty.';
                return 'redirect:register?error=' . urlencode($errorMessage);
            }
        } else {
            $errorMessage = 'Podane hasła nie są takie same.';
            return 'redirect:register?error=' . urlencode($errorMessage);
        }
    }

    $model['user'] = $user;

    return 'register_view';
}

function login(&$model)
{
    $user = [
        'login' => null,
        'password' => null,
    ];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $user['login'] = htmlspecialchars($_POST['login']);
        $user['password'] = $_POST['password'];

        if (authenticate_user($user['login'], $user['password'])) {
            $_SESSION['user'] = $user['login'];

            return 'redirect:photos';
        } else {
            $errorMessage = 'Nieprawidłowy login lub hasło.';
            return 'redirect:login?error=' . urlencode($errorMessage);
        }
    }

    $model['user'] = $user;

    return 'login_view';
}

function user_gallery(&$model)
{
    $model['user_gallery'] = get_user_gallery();
    return 'partial/user_gallery_view';
}

function search(&$model)
{
    $query = $_POST['query'] ?? '';
    $model['query'] = $query;

    if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
        $model['isAjax'] = true;
        $photos = get_photos();
        if (!empty($query)) {
            $searchedPhotos = array_filter($photos, function ($photo) use ($query) {
                return stripos($photo['name'], $query) !== false;
            });

            $model['photos'] = $searchedPhotos;
        } else {
            $model['photos'] = $photos;
        }
        echo json_encode($model['photos']);
        exit;
    } else {
        $model['isAjax'] = false;
        $model['photos'] = get_photos();
    }

    return 'search_view';
}
