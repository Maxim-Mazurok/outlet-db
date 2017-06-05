<?php
require_once('../vendor/autoload.php');
$dotenv = new Dotenv\Dotenv('..');
$dotenv->load();

require_once('../include/db.php');

switch ($_GET['type']) {
    case 'get':
        switch ($_GET['table']) {
            case 'editions':
                $r = pg_query($db, "SELECT * FROM editions");
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
                        INSERT INTO editions 
                        VALUES (
                            '{$_GET['name']}'
                        )"
                    );
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