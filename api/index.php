<?php

/**
 *
 * TODO:
 *
 * product_id text por_0001
 *
 * shoot_name -- shoot_name
 *
 * страницы админки (CMS)
 * + менять пароли для БД
 *
 * хранить файлы не в базе, а на диске
 *
 * http auth
 *
 * no subscr image in images menu
 */

require_once('../vendor/autoload.php');
if ($_ENV['PHP_ENV'] !== 'production') {
    $dotenv = new Dotenv\Dotenv('..');
    $dotenv->load();
}

$s3 = Aws\S3\S3Client::factory([
    'version' => 'latest',
    'region' => 'eu-west-2'
]);
$bucket = getenv('S3_BUCKET_NAME') ?: die('No "S3_BUCKET" config var in found in env!');

function not_empty_get(array $items) {
    foreach ($items as $item) {
        if (!array_key_exists($item, $_GET)) return false;
        if (empty($_GET[$item])) return false;
    }
    return true;
}

function thumbnailImage($imagePath) {
    $imagick = new \Imagick(realpath($imagePath));
    $imagick->setbackgroundcolor('rgb(64, 64, 64)');
    $imagick->thumbnailImage(100, 100, true, true);
    return $imagick->getImageBlob();
}

require_once('../include/db.php');

switch ($_GET['type']) {
    case 'get':
        switch ($_GET['table']) {
            case 'editions':
            case 'edition_menu':
            case 'images_menu':
            case 'social_networks':
            case 'subscriptions_menu':
            case 'videos_menu':
                $r = pg_query($db, "SELECT * FROM {$_GET['table']}");
                echo json_encode(pg_num_rows($r) > 0 ? pg_fetch_all($r) : []);
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
                    'shoot_name',
                    '(upl)video_button',
                    '(upl)subscription_button',
                    '(upl)image_button'
                );

                $sql_fields = array();

                $fields_not_empty = true;
                foreach ($post_fields as $field) {
                    if (substr($field, 0, strlen('(upl)')) === '(upl)') {
                        $field = substr($field, strlen('(upl)'));
                        if (empty($_FILES[$field])) $fields_not_empty = false;
                        $upload = $s3->upload($bucket, $_FILES[$field]['name'], fopen($_FILES[$field]['tmp_name'], 'rb'), 'public-read');
                        array_push($sql_fields, $upload->get('ObjectURL'));
                    } else {
                        if (empty($_POST[$field])) {
                            $fields_not_empty = false;
                        }
                        array_push($sql_fields, $_POST[$field]);
                    }
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
                    'shoot_name',
                    '(gen)thumbnail',
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
                        $upload = $s3->upload($bucket, $_FILES[$field]['name'], fopen($_FILES[$field]['tmp_name'], 'rb'), 'public-read');
                        array_push($sql_fields, $upload->get('ObjectURL'));
                    } elseif (substr($field, 0, strlen('(gen)')) === '(gen)') {
                        $field = substr($field, strlen('(gen)'));
                        $data = thumbnailImage($_FILES['download_image']['tmp_name']);
                        $upload = $s3->upload($bucket, "{$_FILES['download_image']['name']}_thumbnail", $data, 'public-read');
                        array_push($sql_fields, $upload->get('ObjectURL'));
                    } else {
                        if (empty($_POST[$field])) $fields_not_empty = false;
                        array_push($sql_fields, $_POST[$field]);
                    }
                }

                if ($fields_not_empty) {
                    $query = "INSERT INTO {$_GET['table']} VALUES ('" . join("','", $sql_fields) . "')";
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
                        $upload = $s3->upload($bucket, $_FILES[$field]['name'], fopen($_FILES[$field]['tmp_name'], 'rb'), 'public-read');
                        array_push($sql_fields, $upload->get('ObjectURL'));
                    } else {
                        if (empty($_POST[$field])) $fields_not_empty = false;
                        array_push($sql_fields, $_POST[$field]);
                    }
                }

                if ($fields_not_empty) {
                    $query = "INSERT INTO {$_GET['table']} VALUES ('" . join("','", $sql_fields) . "')";
                    pg_query($db, $query);
                }
                break;
            case 'subscriptions_menu':
                $post_fields = array(
                    'edition_name',
                    'model_number',
                    'model_name',
                    'shoot_name',
                    '(gen)thumbnail',
                    '(upl)subscription_image',
                    'product_id'
                );

                $sql_fields = array();

                $fields_not_empty = true;
                foreach ($post_fields as $field) {
                    if (substr($field, 0, strlen('(upl)')) === '(upl)') {
                        $field = substr($field, strlen('(upl)'));
                        if (empty($_FILES[$field])) $fields_not_empty = false;
                        $upload = $s3->upload($bucket, $_FILES[$field]['name'], fopen($_FILES[$field]['tmp_name'], 'rb'), 'public-read');
                        array_push($sql_fields, $upload->get('ObjectURL'));
                    } elseif (substr($field, 0, strlen('(gen)')) === '(gen)') {
                        $field = substr($field, strlen('(gen)'));
                        $data = thumbnailImage($_FILES['subscription_image']['tmp_name']);
                        $upload = $s3->upload($bucket, "{$_FILES['subscription_image']['name']}_thumbnail", $data, 'public-read');
                        array_push($sql_fields, $upload->get('ObjectURL'));
                    } else {
                        if (empty($_POST[$field])) $fields_not_empty = false;
                        array_push($sql_fields, $_POST[$field]);
                    }
                }

                if ($fields_not_empty) {
                    $query = "INSERT INTO {$_GET['table']} VALUES ('" . join("','", $sql_fields) . "')";
                    pg_query($db, $query);
                }
                break;
            case 'videos_menu':
                $post_fields = array(
                    'edition_name',
                    'model_number',
                    'model_name',
                    'shoot_name',
                    'video_title',
                    '(len)length',
                    '(size)size',
                    'price_gbp',
                    'price_usd',
                    'price_eur',
                    '(gen)thumbnail',
                    '(upl)video',
                    'product_id'
                );

                $sql_fields = array();

                $fields_not_empty = true;
                foreach ($post_fields as $field) {
                    if (substr($field, 0, strlen('(upl)')) === '(upl)') {
                        $field = substr($field, strlen('(upl)'));
                        if (empty($_FILES[$field])) $fields_not_empty = false;
                        $upload = $s3->upload($bucket, $_FILES[$field]['name'], fopen($_FILES[$field]['tmp_name'], 'rb'), 'public-read');
                        array_push($sql_fields, $upload->get('ObjectURL'));
                    } elseif (substr($field, 0, strlen('(len)')) === '(len)') {
                        $field = substr($field, strlen('(len)'));
                        $ffprobe = FFMpeg\FFProbe::create();
                        $duration = $ffprobe->format($_FILES['video']['tmp_name'])->get('duration');
                        array_push($sql_fields, intval($duration));
                    } elseif (substr($field, 0, strlen('(size)')) === '(size)') {
                        $field = substr($field, strlen('(size)'));
                        $size = $_FILES['video']['size'];
                        array_push($sql_fields, $size);
                    } elseif (substr($field, 0, strlen('(gen)')) === '(gen)') {
                        $field = substr($field, strlen('(gen)'));
                        $ffmpeg = FFMpeg\FFMpeg::create();
                        $video = $ffmpeg->open($_FILES['video']['tmp_name']);
                        $ffprobe = FFMpeg\FFProbe::create();
                        $duration = $ffprobe->format($_FILES['video']['tmp_name'])->get('duration');
                        $frame = $video->frame(FFMpeg\Coordinate\TimeCode::fromSeconds(intval($duration / 2)));
                        //$thumbnail = pg_escape_bytea(base64_decode($frame->save("{$_FILES['video']['tmp_name']}_frame.jpg", false, true)));
                        $thumbnail_name = "{$_FILES['video']['name']}_frame.jpg";
                        $thumbnail_path = "/tmp/{$thumbnail_name}";
                        $frame->save($thumbnail_path);
                        $upload = $s3->upload($bucket, $thumbnail_name, fopen($thumbnail_path, 'rb'), 'public-read');
                        array_push($sql_fields, $upload->get('ObjectURL'));
                    } else {
                        if (empty($_POST[$field])) $fields_not_empty = false;
                        array_push($sql_fields, $_POST[$field]);
                    }
                }

                if ($fields_not_empty) {
                    $query = "INSERT INTO {$_GET['table']} VALUES ('" . join("','", $sql_fields) . "')";
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