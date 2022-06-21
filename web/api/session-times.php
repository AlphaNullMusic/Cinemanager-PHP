<?php
header("Content-Type:application/json");
header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Methods: GET, HEAD');
date_default_timezone_set('Pacific/Auckland');

require('../inc/web.inc.php');
require('../inc/smarty_vars.inc.php');

// Formats list into JSON and echos
function response($list, $response_code, $response_desc) {
    $response['list'] = $list;
    $response['status'] = $response_code;
    $response['msg'] = $response_desc;

    $json_response = json_encode($response, JSON_PRETTY_PRINT);
    echo $json_response;
    die();
}

$list = get_movie_list_full('ns','m.title',14,'%W %D','%e %b',100,'today',null,null,true,'medium');

if (!$list) { response(NULL, 404, 'Sessions Not Found'); }

$new_list = array();

// Respond with JSON
response($list, 200, 'Success');

?>
