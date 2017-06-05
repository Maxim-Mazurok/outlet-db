<?php
$db = pg_connect("host=" . parse_url($_ENV['DATABASE_URL'])['host'] . " port=" . parse_url($_ENV['DATABASE_URL'])['port'] . " dbname=" . substr(parse_url($_ENV['DATABASE_URL'])['path'], 1) . " user=" . parse_url($_ENV['DATABASE_URL'])['user'] . " password=" . parse_url($_ENV['DATABASE_URL'])['pass']);
?>