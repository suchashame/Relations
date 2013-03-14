<?php

ini_set('display_errors', 0);

require_once "lib/Start.php";

require_once "lib/DbSimple/Generic.php";
require_once "lib/JsHttpRequest.php";

require_once "../chats.php";

$registry->set('igust_2_0', $igust_2_0);


$DB = DbSimple_Generic::connect("mysql://login:pass@host/database");
$DB->query('SET NAMES ?', 'cp1251');

$registry->set('DB', $DB);

$JHR =& new JsHttpRequest("windows-1251");
$registry->set('JHR', $JHR);

$result = new result($registry);
$registry->set('result', $result);


$router = new Router($registry);
$registry->set('router', $router);

$router->setPath(MYPATH . 'lib/services');

$router->delegate();
