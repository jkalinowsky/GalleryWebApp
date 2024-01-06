<?php

function &get_user_gallery()
{
    if (!isset($_SESSION['user_gallery'])) {
        $_SESSION['user_gallery'] = [];
    }

    return $_SESSION['user_gallery'];
}

