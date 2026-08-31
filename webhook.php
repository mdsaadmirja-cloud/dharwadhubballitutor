<?php

use Mpdf\Utils\Arrays;

$arrayObject=[
        'challenge'=>$_GET['hub_challenge']
];

error_log($_GET['hub_challenge']);
if($_GET['hub_verify_token']=="athar" and $_GET['hub_mode']=='subscribe'){
header('Content-Type: application/json');
echo $_GET['hub_challenge'];
http_response_code(200);
}else{
    http_response_code(403);
}
?>