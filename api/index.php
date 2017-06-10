<?php
if ($_ENV['PHP_ENV'] !== 'production') {
    require_once('../vendor/autoload.php');
    $dotenv = new Dotenv\Dotenv('..');
    $dotenv->load();
}

require_once('../include/db.php');

switch ($_GET['type']) {
    case 'get':
        switch ($_GET['table']) {
            case 'editions':
                $r = pg_query($db, "SELECT * FROM editions");
                echo json_encode(pg_num_rows($r) > 0 ? pg_fetch_all($r) : []);
                break;
            case 'edition_menu':
                if (!empty($_GET['file']) && !empty($_GET['edition_name']) && !empty($_GET['model_number'])) {
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
                        INSERT INTO editions 
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
                    $query = "INSERT INTO edition_menu VALUES ('" . join("','", $sql_fields) . "')";
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