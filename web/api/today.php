<?php
header("Content-Type:application/json");
header("Access-Control-Allow-Origin: *");
require('../inc/web.inc.php');
require('../inc/smarty_vars.inc.php');

	$list = get_movie_list_full('ns','m.title',14,'%W %D','%e %b',20,'today',null,null,true,'large');

    if (!$list) { response(NULL, 404, 'Sessions Not Found'); }

    $new_list = array();

    // For each movie in list
    foreach ($list as $key => $value) {

        // Format title and thumbnail
        $list[$key]['title'] = ($list[$key]['classification']) ? $list[$key]['title'].' ('.$list[$key]['classification'].')' : $list[$key]['title'];
        $list[$key]['thumbnail'] = $list[$key]['poster_url'];

        // For each session
        foreach ($list[$key]['sessions'] as $date => $date_data) {

            // Remove all but today
            if ($date != date('Y-m-d')) {
                unset($list[$key]['sessions'][$date]);
            } else {
                foreach ($list[$key]['sessions'][$date] as $session_id => $day) {
                    $list[$key]['sessions'][$date][$session_id]['time'] = ($day['label']) ? $day['time'].' ('.$day['label'].')' : $day['time'];
                    $list[$key]['time'] = ($list[$key]['time']) ? $list[$key]['time'].', '.$list[$key]['sessions'][$date][$session_id]['time'] : $list[$key]['sessions'][$date][$session_id]['time'];
                }
            }
        }

        if (empty($list[$key]['sessions'])) {
            unset($list[$key]);
        }

        // Remove unneded data
        foreach ($value as $attr => $data) {
            if ($attr != 'title' && $attr != 'thumbnail' && $attr != 'time') {
                unset($list[$key][$attr]);
            }
        }
    }

    // Respond with JSON
    response($list, 200, 'Success');

    // Formats list into JSON and echos
    function response($list, $response_code, $response_desc) {
        $response['list'] = $list;
        $response['status'] = $response_code;
        $response['msg'] = $response_desc;

        $json_response = json_encode($response);
        echo $json_response;
    }

?>