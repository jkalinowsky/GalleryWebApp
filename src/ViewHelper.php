<?php

class ViewHelper
{
    public static function renderLoginForm()
    {
        echo '<section class="section">';
        if (!isset($_SESSION['user'])) {
            echo '<a href="register"><button>Zarejestruj się</button></a>';
            echo '<a href="login"><button>Zaloguj się</button></a>';
        } else {
            echo 'Witaj użytkowniku ' . '<b>' . $_SESSION['user'] . '</b>';
            echo '<form method="post" action="">';
            echo '<button type="submit" name="logout">Wyloguj się</button>';
            echo '</form>';
        }
    }

    public static function renderPhotoTable($model)
    {
        $userGalleryIds = isset($model['userGalleryIds']) ? $model['userGalleryIds'] : [];
        $isUserOwner = false;

        foreach ($model['currentPhotos'] as $photo):
            $isChecked = in_array($photo['_id'], $userGalleryIds);
            $isPrivate = ($photo['privacy'] === 'private');
            if (isset($_SESSION['user'])) {
                $isUserOwner = ($_SESSION['user'] === $photo['author']);
            }

            if (!$isPrivate || $isUserOwner):
                ?>
                <input type="hidden" name="id" value="<?= $photo['_id'] ?>"/>
                <tr>
                    <td>
                        <?= $photo['name'] ?> <br>
                        ~ <?= $photo['author'] ?> <br>

                        <?php
                        if ($isPrivate && $isUserOwner):
                            echo '<br><span style="color: red;">Prywatne zdjecie uzytkownika</span>';
                        endif;
                        ?>
                    </td>
                    <td>
                        <a href="view?id=<?= $photo['_id'] ?>"><img src="<?= $photo['thumbnail'] ?>" alt="<?= $photo['name'] ?>" /></a>
                    </td>
                    <td>
                        <a href="edit?id=<?= $photo['_id'] ?>">Edytuj</a> |
                        <a href="delete?id=<?= $photo['_id'] ?>">Usuń</a>
                        <br>
                        <input type="checkbox" name="user_gallery[]" value="<?= $photo['_id'] ?>" <?php echo (isset($_SESSION['user_gallery'][(string)$photo['_id']]) ? 'checked' : ''); ?>>
                    </td>
                </tr>
            <?php
            endif;
        endforeach;

        echo '</tbody></table>';
        echo '<input type="submit" name="add_user_gallery" value="Zapamietaj wybrane"/></form>';
    }

    public static function renderPagination($currentPage, $totalPages)
    {
        echo '<div>Strony: ';

        for ($i = 1; $i <= $totalPages; $i++) {
            $isActive = ($i == $currentPage);
            $queryString = http_build_query(array_merge($_GET, ['page' => $i]));

            echo '<a href="?'.$queryString.'" '.($isActive ? 'class="active"' : '').'>' . $i . '</a> ';
        }

        echo '</div>';
    }
}
?>