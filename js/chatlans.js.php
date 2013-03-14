<?php


require_once "../../chats.php";

$chat = $_REQUEST['chat'];
$md5 = $_REQUEST['md5'];

if ($md5 != md5($chat . $igust_2_0['family'][$chat])) {
    die ("// Õóéíÿ-ñ!");
}

require_once "../lib/DbSimple/Generic.php";

$DB = DbSimple_Generic::connect("mysql://login:pass@host/database");
$DB->query('SET NAMES ?', 'cp1251');


$result = $DB->query("SELECT * FROM chatlans WHERE chat = ?", $chat);

echo "var chl = [" . $result[0]["lans"] . '""];';


