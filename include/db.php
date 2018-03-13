<?php
$_ENV['DATABASE_URL'] = 'postgres://toazkumqwhymaw:f6319264511b2cea778704f9fcc6e49b3e8148543aaff30450170f6ae82dbcef@ec2-54-247-99-159.eu-west-1.compute.amazonaws.com:5432/dmm2lbb30gcpn';
$db = pg_connect("host=" . parse_url($_ENV['DATABASE_URL'])['host'] . " port=" . parse_url($_ENV['DATABASE_URL'])['port'] . " dbname=" . substr(parse_url($_ENV['DATABASE_URL'])['path'], 1) . " user=" . parse_url($_ENV['DATABASE_URL'])['user'] . " password=" . parse_url($_ENV['DATABASE_URL'])['pass']);
?>