<?php

require_once "/home/igust4u/data/public_html/service/chats.php";
require_once "/home/igust4u/data/public_html/service/igust-2.0/lib/DbSimple/Generic.php";

$DB = DbSimple_Generic::connect("mysql://login:pass@host/database");
$DB->query('SET NAMES ?', 'cp1251');

$chats = $igust_2_0['family'];

$content = $md5 = $chat = "";

foreach ($chats as $k => $v) {
    $md5 = md5("chatlans" . $v);
    $chat = "http://$k.august4u.ru/page/1?page=$md5";

    $result = trim(file_get_contents($chat, null, null, null, 38));

    if ($result != md5("result" . $v)) {
        continue;
    }

    $content = strip_tags(trim(file_get_contents($chat, null, null, 38)));


    if (!@$DB->query("UPDATE chatlans SET lans=? WHERE chat=?", $content, $k)) {
        $DB->query('INSERT INTO chatlans(chat, lans) VALUES(?, ?)', $k, $content);
    }
}

?>
