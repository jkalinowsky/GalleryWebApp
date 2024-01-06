<?php

function generate_watermark($photo, $watermarkDir)
{
    $imagePath = $photo['photo'];
    $imageData = file_get_contents($imagePath);
    $image = imagecreatefromstring($imageData);

    // watermark options
    $font = 80;
    $angle = 45;
    $margin = 10;
    $x = $margin;
    $y = imagesy($image) - $margin;
    $color = imagecolorallocatealpha($image, 255, 255, 255, 64); // White color

    $fontPath = __DIR__ . '/fonts/arial.ttf';
    chmod($fontPath, 0644);
    imagettftext($image, $font, $angle, $x, $y, $color, $fontPath, $photo['watermark_text']);

    $watermarkPath = $watermarkDir . $photo['name'] . '_watermark.jpg';
    imagejpeg($image, $watermarkPath);

    imagedestroy($image);

    return $watermarkPath;
}

function generate_thumbnail($photo, $thumbnailDir)
{
    $imagePath = $photo['photo'];
    $imageData = file_get_contents($imagePath);
    $image = imagecreatefromstring($imageData);

    $originalWidth = imagesx($image);
    $originalHeight = imagesy($image);

    $thumbnailWidth = 200;
    $thumbnailHeight = 125;

    $thumbnail = imagecreatetruecolor($thumbnailWidth, $thumbnailHeight);

    imagecopyresampled($thumbnail, $image, 0, 0, 0, 0, $thumbnailWidth, $thumbnailHeight, $originalWidth, $originalHeight);

    $thumbnailPath = $thumbnailDir . $photo['name'] . '_thumbnail.jpg';
    imagejpeg($thumbnail, $thumbnailPath);

    imagedestroy($image);
    imagedestroy($thumbnail);

    return $thumbnailPath;
}

function createDirectory($dir)
{
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
}

function checkAndFixPermissions($dir)
{
    if (!is_writable($dir)) {
        chmod($dir, 0755);
    }
}

function delete_photo_files($id)
{
    $photo = get_photo($id);
    if (isset($photo['photo'])) {
        $photoPath = $photo['photo'];
        unlink($photoPath);
    }

    if (isset($photo['thumbnail'])) {
        $thumbnailPath = $photo['thumbnail'];
        unlink($thumbnailPath);
    }

    if (isset($photo['watermark'])) {
        $thumbnailPath = $photo['watermark'];
        unlink($thumbnailPath);
    }
}

function logout(&$model)
{
    session_unset();
    session_destroy();
    return 'redirect:photos';
}

function add_user_gallery(&$model)
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_user_gallery'])) {
        $selectedPhotos = isset($_POST['user_gallery']) ? $_POST['user_gallery'] : [];

        if (empty($selectedPhotos)) {
            return is_ajax() ? user_gallery($model) : 'redirect:' . $_SERVER['HTTP_REFERER'];
        }

        $user_gallery = &get_user_gallery();

        foreach ($selectedPhotos as $id) {
            $photo = get_photo($id);
            $user_gallery[$id] = ['_id' => $photo['_id'], 'name' => $photo['name'], 'author' => $photo['author'], 'thumbnail' => $photo['thumbnail']];
        }
        return is_ajax() ? user_gallery($model) : 'redirect:' . $_SERVER['HTTP_REFERER'];
    }
}

function clear_user_gallery(&$model)
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['clear_user_gallery'])) {
        $selectedPhotos = isset($_POST['user_gallery']) ? $_POST['user_gallery'] : [];

        if (empty($selectedPhotos)) {
            $_SESSION['user_gallery'] = [];
        } else {
            $user_gallery = &get_user_gallery();

            foreach ($selectedPhotos as $id) {
                // Unset the selected photos from the user's gallery
                unset($user_gallery[$id]);
            }
        }
        return is_ajax() ? user_gallery($model) : 'redirect:' . $_SERVER['HTTP_REFERER'];
    }
}
