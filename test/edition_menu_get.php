<?php
if ($_ENV['PHP_ENV'] !== 'production') {
    require_once('../vendor/autoload.php');
    $dotenv = new Dotenv\Dotenv('..');
    $dotenv->load();
}

require_once('../include/db.php');

$sql = "SELECT * FROM edition_menu";


$resource = pg_query($db, $sql);
$row = pg_fetch_array($resource, NULL, PGSQL_BOTH);

//var_dump($row);

$data = pg_unescape_bytea($row['video_button']);

$mime_type = finfo_buffer(finfo_open(), $data, FILEINFO_MIME_TYPE);

var_dump($mime_type);die();

//$data = pg_unescape_bytea($row['subscription_button']);
//$data = pg_unescape_bytea($row['image_button']);

$extension = 'png';
$fileId = 'subscription_button';

// We'll be outputting a PDF
header('Content-type: image/png');


// It will be called downloaded.pdf
//header('Content-Disposition: attachment; filename="downloaded.png"');
echo $data;
exit;