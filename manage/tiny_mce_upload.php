<?php
include("inc/manage.inc.php");

$accepted_origins = [
	'http://localhost',
	'https://manage.shoreline.nz',
	'https://shorelinecinema.co.nz',
	'https://www.shorelinecinema.co.nz',
];

if (isset($_SERVER['HTTP_ORIGIN'])) {
    // same-origin requests won't set an origin. If the origin is set, it must be valid.
    if (in_array($_SERVER['HTTP_ORIGIN'], $accepted_origins)) {
        header('Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN']);
    } else {
        header("HTTP/1.1 403 Origin Denied");
        return;
    }
}

// Don't attempt to process the upload on an OPTIONS request
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    header("Access-Control-Allow-Methods: POST, OPTIONS");
    return;
}

reset($_FILES);
$temp = current($_FILES);

if (is_uploaded_file($temp['tmp_name'])) {
    if (preg_match("/([^\w\s\d\-_~,;:\[\]\(\).])|([\.]{2,})/", $temp['name'])) {
        header("HTTP/1.1 400 Invalid file name.");
        return;
    }

    // Validating File extensions
    if (! in_array(strtolower(pathinfo($temp['name'], PATHINFO_EXTENSION)), array(
        "gif",
        "jpg",
        "png"
    ))) {
        header("HTTP/1.1 400 Invalid extension.");
        return;
    }

    $fileName = "uploads/" . $temp['name'];
    move_uploaded_file($temp['tmp_name'], $fileName);
    $fileUrl = $config['manage_url'] . $fileName;

    // Return JSON response with the uploaded file path.
    echo json_encode(array(
        //'file_path' => $fileUrl
	'location' => $fileUrl
    ));
} else {
	header("HTTP/1.1 500 Server Error");
}
?>
