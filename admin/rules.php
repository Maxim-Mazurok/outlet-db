<?php

require_once '../include/db.php';

if (!empty($_REQUEST['text'])) {
    $text = pg_escape_string($db, $_REQUEST['text']);
    pg_query($db, "UPDATE rules SET text = '$text'");
}

$res = pg_query($db, "SELECT * FROM rules LIMIT 1");
$data = pg_fetch_all($res)[0]['text'];

?>
<!DOCTYPE html>
<html>
<head>
    <script src="https://cloud.tinymce.com/stable/tinymce.min.js?apikey=e9cm3acfulyqvgy5hy0i9acn4hrwkk3924xva5s8ty2k3gjl"></script>
    <script>tinymce.init({selector: 'textarea', height : "60vh"});</script>
</head>
<body>
<a style="margin-bottom: 1em;" href="/admin"><< Back to admin</a>
<form method="post">
    <label>
        Terms Of Use:
        <textarea name="text"><?= $data ?></textarea>
    </label>
    <input type="submit" value="Save">
</form>
</body>
</html>