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
var_dump($_GET);
var_dump($_POST);
var_dump($_FILES);
?>
</pre>
<form action="/api/index.php?type=add&table=videos_menu" method="post" enctype="multipart/form-data">
    <input type="text" name="edition_name">
    <input type="number" name="model_number">
    <input type="text" name="model_name">
    <input type="text" name="short_name">
    <input type="text" name="video_title">
    <input type="number" name="price_gbp">
    <input type="number" name="price_usd">
    <input type="number" name="price_eur">
    <input type="file" name="video">
    <input type="text" name="product_id">
    <input type="submit">
</form>
</body>
</html>