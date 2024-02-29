<?php
header("Content-Type:application/json");
header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Methods: GET, HEAD');
date_default_timezone_set('Pacific/Auckland');
require('../../inc/web.inc.php');
require('../../inc/smarty_vars.inc.php');

    if (isset($_REQUEST['rand'])) {
        echo $_REQUEST['rand'];
    }

$pages = get_page_list();

if (!$pages) { response(NULL, 404, 'No Pages Found'); }

// Respond with JSON
response($pages, 200, 'Success');

// Formats list into JSON and echos
function response($list, $response_code, $response_desc) {
    $response['list'] = $list;
    $response['status'] = $response_code;
    $response['msg'] = $response_desc;

    $json_response = json_encode($response);
    echo $json_response;
    die();
}

?>
