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
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,700&amp;subset=cyrillic,latin-ext"
          rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/7.0.0/normalize.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.1.20/jquery.fancybox.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.28.15/js/jquery.tablesorter.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.1.20/jquery.fancybox.min.js"></script>
    <script src="../res/jQuery-Autocomplete-1.4.2/dist/jquery.autocomplete.min.js"></script>
    <title>Outlet DB Admin</title>
    <script>
        $(document)
            .on('click', 'input[type="submit"]', function (e) {
                e.preventDefault();
                var form = $(this).closest('form');
                /*$.post($(form).attr('action'), $(form).serialize());*/
                var fd = new FormData(form.get(0));
                $(this).attr('value', 'submitting...').addClass('submitting');
                var thiz = $(this);
                $.ajax({
                    url: $(form).attr('action') + '&batch=true',
                    data: fd,
                    cache: false,
                    processData: false,
                    contentType: false,
                    type: $(form).attr('method').toString().toUpperCase(),
                    beforeSend: function (xhr) {
                        xhr.setRequestHeader("Authorization", "Basic " + btoa("api-user:StrongPassword2017"));
                    },
                    complete: function () {
                        $(thiz).attr('value', 'done!');
                        window.location.reload()
                    }
                });
            })
            .on('click', 'span.edit', function () {
                var id = $(this).data('item-id');
                $(this).text('save').removeClass('edit').addClass('save');
                $(this).closest('tr').children().each(function () {
                    switch ($(this).data('type')) {
                        case 'image':
                        case 'video':
                            $(this).append('<input name="' + $(this).data('column') + '" type="file">');
                            break;
                        default:
                            if ($(this).data('column') !== undefined) {
                                $(this).attr('contenteditable', 'true');
                            }
                            break;
                    }
                })

            })
            .on('click', 'span.save', function () {
                var id = $(this).data('item-id');
                var fd = new FormData();
                $(this).closest('tr').children().each(function () {
                    switch ($(this).data('type')) {
                        case 'image':
                        case 'video':
                            if ($(this).data('column') !== 'thumbnail' || $(this).data('column') === 'thumbnail' && document.location.href.indexOf('videos_menu') !== -1) {
                                var file = $(this).find('input')[0].files[0];
                                if (file !== undefined) {
                                    fd.append($(this).data('column').toString(), file);
                                }
                                $(this).find('input').hide();
                            }
                            break;
                        default:
                            if ($(this).data('column') !== undefined) {
                                $(this).removeAttr('contenteditable');
                                fd.append($(this).data('column').toString(), $(this).text());
                            }
                            break;
                    }
                });

                $(this).text('saving...').removeClass('save').addClass('saving');

                var thiz = this;
                $.ajax({
                    url: $('form').attr('action').replace('type=add', 'type=edit') + '&id=' + id,
                    data: fd,
                    cache: false,
                    processData: false,
                    contentType: false,
                    type: 'POST',
                    beforeSend: function (xhr) {
                        xhr.setRequestHeader("Authorization", "Basic " + btoa("api-user:StrongPassword2017"));
                    },
                    complete: function (res) {
                        $(thiz).text('saved!').removeClass('saving').addClass('saved');
                        if (res.responseText.trim() === 'reload') {
                            window.location.reload();
                        } else if (res.responseText.trim().length > 0) {
                            res = JSON.parse(res.responseText);
                            if (res.length > 0) {
                                res.forEach(function (dat) {
                                    var column = dat[0];
                                    var img = dat[1];
                                    $(thiz).closest('tr').find('td[data-column="' + column + '"]').find('img').attr('src', img);
                                });
                            }
                        }

                        setTimeout(function () {
                            $(thiz).text('edit').removeClass('save').addClass('edit');
                        }, 1000);
                    }
                });
            })
            .on('click', 'span.delete', function () {
                $(this).text('sure?').removeClass('delete').addClass('sure');
            })
            .on('click', 'span.sure', function () {
                $(this).text('deleting...').removeClass('sure').addClass('deleting');
                var id = $(this).data('item-id');
                var thiz = this;
                $.ajax({
                    url: $('form').attr('action').replace('type=add', 'type=delete') + '&id=' + id,
                    cache: false,
                    processData: false,
                    contentType: false,
                    type: 'GET',
                    beforeSend: function (xhr) {
                        xhr.setRequestHeader("Authorization", "Basic " + btoa("api-user:StrongPassword2017"));
                    },
                    complete: function () {
                        $(thiz).text('deleted!').removeClass('deleting').addClass('deleted');
                        setTimeout(function () {
                            $(thiz).closest('tr').hide();
                        }, 1000);
                    }
                });
            })
            .on('change', 'select#limit', function () {
                var select = $('#limit');
                var current = $(select).find("option[selected]").val();
                var selected = $(select).find(":selected").val();
                if (current === undefined || current === 'none' || current === '0') {
                    window.location += '?limit=' + selected;
                } else if (current !== selected) {
                    window.location = window.location.href.replace('limit=' + current, 'limit=' + selected);
                }
            });
        $(document).ready(function () {
                $('.tablesorter').tablesorter();
            }
        );
    </script>
    <style>
        .autocomplete-suggestions {
            overflow: auto;
            border: 1px solid #CBD3DD;
            background: #FFF;
        }

        .autocomplete-suggestion {
            overflow: hidden;
            padding: 5px 15px;
            white-space: nowrap;
        }

        .autocomplete-selected {
            background: #F0F0F0;
        }

        .autocomplete-suggestions strong {
            color: #029cca;
            font-weight: normal;
        }

        body {
            font-family: 'Open Sans', sans-serif;
        }

        #main_menu {
            background-color: #2980b9;
            width: 100%;
            padding: 0;
            margin: 0;
        }

        #main_menu li {
            box-sizing: border-box;
            display: inline-block;
            list-style: none;
            width: calc(100% / 6);
            padding: 0 calc(10% / 6);
            text-align: center;
        }

        #main_menu li a {
            display: inline-block;
            width: 100%;
            height: 100%;
            padding: 1em 0;
            color: #ffffff;
            text-decoration: none;
            vertical-align: middle;
        }

        #main_menu li.selected {
            font-weight: bold;
            background-color: #3498db;
        }

        main {
            padding: 2em 4em;
        }

        form {
            margin-bottom: 4em;
        }

        label {
            display: block;
            margin-bottom: 1em;
        }

        label + input {
            display: block;
            margin-bottom: 1em;
            width: 50%;
        }

        label[for='limit'],
        label[for='limit'] + input {
            display: inline-block;
            margin-right: 1em;
        }

        input[type='submit'] {
            display: block;
            margin-top: 1em;
            background-color: #27ae60;
            border-radius: 10px;
            padding: 1em 2em;
            font-weight: bold;
            color: #ffffff;
            cursor: pointer;
        }

        input[type='submit']:hover {
            background-color: #2ecc71;
        }

        #items_list td img {
            cursor: zoom-out;
        }

        #items_list td img[height="100"] {
            cursor: zoom-in;
        }

        #items_list td {
            text-align: center;
        }

        #items_list tr:hover {
            background-color: #eee;
        }

        span.edit, span.save, span.delete, span.sure {
            cursor: pointer;
        }

        span.saving, span.deleting {
            cursor: wait;
        }

        span.edit {
            color: #2980b9;
        }

        span.save {
            background-color: #2ecc71;
            color: #000;
        }

        span.delete {
            color: #e74c3c;
        }

        span.sure {
            background-color: #e74c3c;
            font-weight: bold;
            color: #fff;
        }

        .tablesorter th {
            cursor: pointer;
        }

        table.limit5 tr:nth-child(n+5) {
            display: none;
        }

        table.limit10 tr:nth-child(n+10) {
            display: none;
        }

        table.limit20 tr:nth-child(n+20) {
            display: none;
        }
    </style>
</head>
<body>
<ul id="main_menu">
    <li <?= ($current_item === 'editions' ? 'class="selected"' : '') ?>>
        <a href="/admin/editions">
            Editions
        </a>
        <?php echo "</li><!----><li" ?>
        <?= ($current_item === 'edition_menu' ? 'class="selected"' : '') ?>>
        <a href="/admin/edition_menu">
            Edition Menu
        </a>
        <?php echo "</li><!----><li" ?>
        <?= ($current_item === 'images_menu' ? 'class="selected"' : '') ?>>
        <a href="/admin/images_menu">
            Images Menu
        </a>
        <?php echo "</li><!----><li" ?>
        <?= ($current_item === 'social_networks' ? 'class="selected"' : '') ?>>
        <a href="/admin/social_networks">
            Social Networks
        </a>
        <?php echo "</li><!----><li" ?>
        <?= ($current_item === 'subscriptions_menu' ? 'class="selected"' : '') ?>>
        <a href="/admin/subscriptions_menu">
            Subscriptions Menu
        </a>
        <?php echo "</li><!----><li" ?>
        <?= ($current_item === 'videos_menu' ? 'class="selected"' : '') ?>>
        <a href="/admin/videos_menu">
            Videos Menu
        </a>
    </li>
</ul>
<main>
    <?php
    switch ($current_item) {
        case 'editions':
        case 'edition_menu':
        case 'images_menu':
        case 'social_networks':
        case 'subscriptions_menu':
        case 'videos_menu':
            $username = 'api-user';
            $password = 'StrongPassword2017';

            $context = stream_context_create(array(
                'http' => array(
                    'header' => "Authorization: Basic " . base64_encode("$username:$password")
                )
            ));
            $url = "https://outlet-db.herokuapp.com/api/?type=get&table=$current_item";
            $json = file_get_contents($url, false, $context);
            $data = json_decode($json);
            $columns = array_keys((array)$data[0]);

            $suggestions = [];

            foreach ($columns as $column) {
                $suggestions[$column]['lookup'] = [];
                foreach ($data as $datum) {
                    if (in_array(['value' => $datum->$column], $suggestions[$column]['lookup']) === false) {
                        array_push($suggestions[$column]['lookup'], ['value' => $datum->$column]);
                    }
                }
            }

            foreach ($suggestions as $suggestion_name => $values) {
                if ($suggestion_name === 'edition_name') {
                    echo "<script>
                    $(document).ready(function () {
                        if ($('input[name=edition_name]').length > 0 || $('input[type=text]').length === 1) {
                            $('input[name=edition_name], input[name=name]').autocomplete(" . json_encode($values) . ");
                        }
                    });
                    </script>";
                } else {
                    echo "<script>
                    $(document).ready(function () {
                        $('input[name=$suggestion_name]').autocomplete(" . json_encode($values) . ");
                    });
                    </script>";
                }
            }

            echo "<h1>Add new $current_item item:</h1>" . PHP_EOL;
            echo "<form autocomplete='off' method='post' action='/api/?type=add&table=$current_item'>" . PHP_EOL;
            foreach ($columns as $column) {
                switch ($column) {
                    case 'id':
                    case 'length':
                    case 'size':
                        break;
                    case 'price_gbp':
                    case 'price_usd':
                    case 'price_eur':
                        echo "<label for='$column'>$column:</label>" . PHP_EOL;
                        echo "<input name='$column' id='$column' type='number'>" . PHP_EOL;
                        break;
                    case 'video_button':
                    case 'subscription_button':
                    case 'image_button':
                    case 'icon_color':
                    case 'thumbnail_grey':
                    case 'subscription_image':
                    case 'video':
                    case 'download_image':
                        echo "<label for='$column'>$column:</label>" . PHP_EOL;
                        echo "<input multiple name='{$column}[]' id='$column' type='file'>" . PHP_EOL;
                        break;
                    case 'product_id':
                        echo "<label for='$column'>$column prefix:</label>" . PHP_EOL;
                        echo "<input name='$column' id='$column'>" . PHP_EOL;
                        break;
                    default:
                        if ($column === 'thumbnail' && $current_item === 'videos_menu') {
                            echo "<label for='$column'>$column:</label>" . PHP_EOL;
                            echo "<input multiple name='{$column}[]' id='$column' type='file'>" . PHP_EOL;
                        } elseif ($column === 'thumbnail') {

                        } else {
                            echo "<label for='$column'>$column:</label>" . PHP_EOL;
                            echo "<input type='text' autocomplete='off' name='$column' id='$column'>" . PHP_EOL;
                        }
                        break;
                }
            }
            echo "<input type='submit' value='submit'>" . PHP_EOL;
            echo "</form>" . PHP_EOL;
            echo "<h1>List of $current_item items:</h1>" . PHP_EOL;
            echo "<label for='limit'>Rows limit:</label>";
            echo "<select name='limit' id='limit'>" . PHP_EOL;
            echo "<option " . (!(!isset($_GET['limit']) || 'none' === $_GET['limit']) ?: 'selected') . " value='0'>none</option>";
            $limits = [5, 10, 20, 50, 100];
            foreach ($limits as $limit) {
                echo "<option " . (!(isset($_GET['limit']) && $limit === intval($_GET['limit'])) ?: 'selected') . " value='$limit'>$limit</option>";
            }
            echo "</select>" . PHP_EOL;
            echo "<table border='1' cellpadding='5' class='tablesorter " . (!isset($_GET['limit']) ?: "limit{$_GET['limit']}") . "'>" . PHP_EOL;
            echo "<thead>" . PHP_EOL;
            echo "<tr>" . PHP_EOL;
            echo "<th></th>" . PHP_EOL;
            echo "<th></th>" . PHP_EOL;
            foreach ($columns as $column) {
                switch ($column) {
                    case 'id':
                        break;
                    default:
                        echo "<th>$column</th>" . PHP_EOL;
                        break;
                }
            }
            echo "</tr>" . PHP_EOL;
            echo "</thead>" . PHP_EOL;
            echo "<tbody id='items_list'>" . PHP_EOL;
            foreach ($data as $datum) {
                echo "<tr>" . PHP_EOL;
                echo "<td><span class='delete' data-item-id='{$datum->id}'>delete </span></td>" . PHP_EOL;
                echo "<td><span class='edit' data-item-id='{$datum->id}'>edit</span></td>" . PHP_EOL;
                foreach ($columns as $column) {
                    switch ($column) {
                        case 'id':
                            break;
                        case 'video_button':
                        case 'subscription_button':
                        case 'image_button':
                        case 'thumbnail':
                        case 'download_image':
                        case 'icon_color':
                        case 'thumbnail_grey':
                        case 'subscription_image':
                            echo "<td data-type='image' data-column='{$column}'><a data-fancybox='gallery' href='{$datum->$column}'><img title='click to view full size image' height='100' src='{$datum->$column}'></a></td>" . PHP_EOL;
                            break;
                        case 'video':
                            echo "<td data-type='video' data-column='{$column}'><video height='240' controls><source src='{$datum->$column}'></video></td>" . PHP_EOL;
                            break;
                        default:
                            echo "<td data-column='{$column}'>{$datum->$column}</td>" . PHP_EOL;
                            break;
                    }
                }
                echo "</tr>" . PHP_EOL;
            }
            echo "</tbody>" . PHP_EOL;
            echo "</table>" . PHP_EOL;
            ?>

            <?php
            break;
        default:
            break;
    }
    ?>
</main>
</body>
</html>