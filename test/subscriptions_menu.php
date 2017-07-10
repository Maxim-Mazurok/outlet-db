<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>test page</title>
    <style>
        input {
            display: block;
        }
        input[type=submit] {
            margin-top: 1em;
        }
    </style>
</head>
<body>
<pre>
<?php
ini_set('file_uploads', 'On');
ini_set('upload_max_filesize', '50M');
ini_set('post_max_size', '50M');
var_dump($_GET);
var_dump($_POST);
var_dump($_FILES);
?>
</pre>
<form action="/api/index.php?type=add&table=subscriptions_menu" method="post" enctype="multipart/form-data">
    edition_name: <input type="text" name="edition_name">
    model_number: <input type="number" name="model_number">
    model_name: <input type="text" name="model_name">
    shoot_name: <input type="text" name="shoot_name">
    subscription_image: <input type="file" name="subscription_image">
    product_id: <input type="number" name="product_id">
    <input type="submit">
</form>
</body>
</html>