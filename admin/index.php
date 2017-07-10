<?php
$url = $_SERVER['REQUEST_URI'];
$re = '/\/admin\/([a-z_]+)\/?/';
preg_match_all($re, $url, $matches, PREG_SET_ORDER, 0);
$current_item = $matches[0][1];
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Outlet DB Admin</title>
    <style>
        #main_menu li {
            display: inline-block;
            list-style: none;
            margin: 1em;
        }

        #main_menu li.selected {
            font-weight: bold;
        }
    </style>
</head>
<body>
<ul id="main_menu">
    <li <?= ($current_item === 'editions' ? 'class="selected"' : '') ?>>
        <a href="/admin/editions">
            Editions
        </a>
    </li>
    <li <?= ($current_item === 'edition_menu' ? 'class="selected"' : '') ?>>
        <a href="/admin/edition_menu">
            Edition Menu
        </a>
    </li>
    <li <?= ($current_item === 'images_menu' ? 'class="selected"' : '') ?>>
        <a href="/admin/images_menu">
            Images Menu
        </a>
    </li>
    <li <?= ($current_item === 'social_networks' ? 'class="selected"' : '') ?>>
        <a href="/admin/social_networks">
            Social Networks
        </a>
    </li>
    <li <?= ($current_item === 'subscriptions_menu' ? 'class="selected"' : '') ?>>
        <a href="/admin/subscriptions_menu">
            Subscriptions Menu
        </a>
    </li>
    <li <?= ($current_item === 'videos_menu' ? 'class="selected"' : '') ?>>
        <a href="/admin/videos_menu">
            Videos Menu
        </a>
    </li>
</ul>
</body>
</html>