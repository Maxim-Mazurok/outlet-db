<?php
if ($_ENV['PHP_ENV'] !== 'production') {
    require_once('../vendor/autoload.php');
    $dotenv = new Dotenv\Dotenv('..');
    $dotenv->load();
}

ini_set('file_uploads', 'On');
ini_set('upload_max_filesize', '50M');
ini_set('post_max_size', '50M');

function not_empty_get(array $items) {
    foreach ($items as $item) {
        if (!array_key_exists($item, $_GET)) return false;
        if (empty($_GET[$item])) return false;
    }
    return true;
}

require_once('../include/db.php');

switch ($_GET['type']) {
    case 'get':
        switch ($_GET['table']) {
            case 'editions':
                $r = pg_query($db, "SELECT * FROM {$_GET['table']}");
                echo json_encode(pg_num_rows($r) > 0 ? pg_fetch_all($r) : []);
                break;
            case 'edition_menu':
                if (not_empty_get(['file', 'edition_name', 'model_number'])) {
                    switch ($_GET['file']) {
                        case 'video_button':
                        case 'subscription_button':
                        case 'image_button':
                            $r = pg_query($db, "SELECT {$_GET['file']} FROM edition_menu WHERE edition_name = '{$_GET['edition_name']}' AND model_number = '{$_GET['model_number']}'");
                            $res = pg_fetch_assoc($r);
                            $data = pg_unescape_bytea($res[$_GET['file']]);
                            $mime_type = finfo_buffer(finfo_open(), $data, FILEINFO_MIME_TYPE);
                            header("Content-type: {$mime_type}", true);
                            echo $data;
                            break;
                        default:
                            die(404);
                            break;
                    }
                } else {
                    $r = pg_query($db, "SELECT edition_name, model_number, model_name, short_name FROM edition_menu");
                    echo json_encode(pg_num_rows($r) > 0 ? pg_fetch_all($r) : []);
                }
                break;
            case 'images_menu':
                if (not_empty_get(['edition_name', 'model_number', 'product_id'])) {
                    switch ($_GET['file']) {
                        case 'thumbnail':
                        case 'subscription_image':
                        case 'download_image':
                            $f = $_GET['file'];
                            $r = pg_query($db, "SELECT $f FROM images_menu WHERE edition_name = '{$_GET['edition_name']}' AND model_number = '{$_GET['model_number']}' AND product_id = '{$_GET['product_id']}'");
                            $res = pg_fetch_assoc($r);
                            $data = pg_unescape_bytea($res[$_GET['file']]);
                            $mime_type = finfo_buffer(finfo_open(), $data, FILEINFO_MIME_TYPE);
                            header("Content-type: {$mime_type}", true);
                            echo $data;
                            break;
                        default:
                            die(404);
                            break;
                    }
                } else {
                    $r = pg_query($db, "SELECT edition_name, model_number, model_name, short_name, product_id, price_gbp, price_usd, price_eur FROM images_menu");
                    echo json_encode(pg_num_rows($r) > 0 ? pg_fetch_all($r) : []);
                }
                break;
            case 'social_networks':
                if (not_empty_get(['name'])) {
                    switch ($_GET['file']) {
                        case 'thumbnail_grey':
                            $f = $_GET['file'];
                            $r = pg_query($db, "SELECT $f FROM {$_GET['table']} WHERE name = '{$_GET['name']}'");
                            $res = pg_fetch_assoc($r);
                            $data = pg_unescape_bytea($res[$_GET['file']]);
                            $mime_type = finfo_buffer(finfo_open(), $data, FILEINFO_MIME_TYPE);
                            header("Content-type: {$mime_type}", true);
                            echo $data;
                            break;
                        default:
                            die(404);
                            break;
                    }
                } else {
                    $r = pg_query($db, "SELECT name, url, icon_color FROM {$_GET['table']}");
                    echo json_encode(pg_num_rows($r) > 0 ? pg_fetch_all($r) : []);
                }
                break;
            default:
                die(404);
                break;
        }
        break;

    case 'add':
        switch ($_GET['table']) {
            case 'editions':
                if (!empty($_GET['name'])) {
                    pg_query($db, "
                        INSERT INTO {$_GET['table']} 
                        VALUES (
                            '{$_GET['name']}'
                        )"
                    );
                }
                break;
            case 'edition_menu':
                $post_fields = array(
                    'edition_name',
                    'model_number',
                    'model_name',
                    'short_name'
                );
                $upload_fields = array(
                    'video_button',
                    'subscription_button',
                    'image_button'
                );

                $sql_fields = array();

                $fields_not_empty = true;
                foreach ($post_fields as $field) {
                    if (empty($_POST[$field])) {
                        $fields_not_empty = false;
                    }
                    array_push($sql_fields, $_POST[$field]);
                }
                foreach ($upload_fields as $field) {
                    if (empty($_FILES[$field])) {
                        $fields_not_empty = false;
                    }
                    $data = file_get_contents($_FILES[$field]['tmp_name']);
                    if (!$data) {
                        $fields_not_empty = false;
                    }
                    array_push($sql_fields, pg_escape_bytea($data));
                }

                if ($fields_not_empty) {
                    $query = "INSERT INTO {$_GET['table']} VALUES ('" . join("','", $sql_fields) . "')";
                    pg_query($db, $query);
                }
                break;
            case 'images_menu':
                $post_fields = array(
                    'edition_name',
                    'model_number',
                    'model_name',
                    'short_name',
                    '(upl)thumbnail',
                    '(upl)subscription_image',
                    '(upl)download_image',
                    'product_id',
                    'price_gbp',
                    'price_usd',
                    'price_eur'
                );

                $sql_fields = array();

                $fields_not_empty = true;
                foreach ($post_fields as $field) {
                    if (substr($field, 0, 5) === '(upl)') {
                        $field = substr($field, 5);
                        if (empty($_FILES[$field])) $fields_not_empty = false;
                        $data = file_get_contents($_FILES[$field]['tmp_name']);
                        if (!$data) $fields_not_empty = false;
                        array_push($sql_fields, pg_escape_bytea($data));
                    } else {
                        if (empty($_POST[$field])) $fields_not_empty = false;
                        array_push($sql_fields, $_POST[$field]);
                    }
                }

                if ($fields_not_empty) {
                    $query = "INSERT INTO {$_GET['table']} VALUES ('" . join("','", $sql_fields) . "')";
                    //die($query);
                    pg_query($db, $query);
                }
                break;
            case 'social_networks':
                $post_fields = array(
                    'name',
                    'url',
                    'icon_color',
                    '(upl)thumbnail_grey'
                );

                $sql_fields = array();

                $fields_not_empty = true;
                foreach ($post_fields as $field) {
                    if (substr($field, 0, 5) === '(upl)') {
                        $field = substr($field, 5);
                        if (empty($_FILES[$field])) $fields_not_empty = false;
                        $data = file_get_contents($_FILES[$field]['tmp_name']);
                        if (!$data) $fields_not_empty = false;
                        array_push($sql_fields, pg_escape_bytea($data));
                    } else {
                        if (empty($_POST[$field])) $fields_not_empty = false;
                        array_push($sql_fields, $_POST[$field]);
                    }
                }

                if ($fields_not_empty) {
                    $query = "INSERT INTO {$_GET['table']} VALUES ('" . join("','", $sql_fields) . "')";
                    //die($query);
                    pg_query($db, $query);
                }
                break;
            default:
                die(404);
                break;
        }
        break;
    default:
        die(404);
        break;
}
?>