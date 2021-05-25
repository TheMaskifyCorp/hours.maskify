<?php

require_once $_SERVER['DOCUMENT_ROOT']."/app/init.php";

if (isset($_GET['apipath'])) {
    $apipath = $_GET['apipath'];
}
else {
    $apipath = "";
};

$apiVars=explode('/',$apipath);
if (count($apiVars) > 3) return header("Location: /404.php");
$api = new API\API;
if (count($apiVars) == 3 AND ($apiVars[1]=="D") || ($apiVars[1]=="d"))
{
    $response = $api->endpoint($apiVars[0])->request("get")->department($apiVars[2])->execute();
}
elseif ( ( count( $apiVars ) == 2 ) AND ( preg_match('/[0-9]+/',$apiVars[1] ) ) )
{
    $response = $api->endpoint($apiVars[0])->request("get")->itemID((int)$apiVars[1])->execute();
}
elseif ( count( $apiVars ) == 1 ){
    $response = $api->endpoint($apiVars[0])->request("get")->execute();
}
else $response = $apiVars;
?>
<pre>
    <?php
   echo json_encode($response,JSON_PRETTY_PRINT); ?>
</pre>