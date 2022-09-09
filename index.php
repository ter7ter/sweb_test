<?php
require_once "src/SwebApi.php";

$api = new SwebApi('ter766terg', 'fZCJ^75ndSdLt3Aq');
$token = $api->getToken();
var_dump($token);
$result = $api->move('a159014db5dc1.ru', 'manual');
echo $result ."\n";
