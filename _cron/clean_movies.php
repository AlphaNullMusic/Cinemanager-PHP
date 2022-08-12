#!/usr/bin/php7.2
<?php

ini_set('memory_limit','512M');
ini_set('max_execution_time',600);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$root_dir = dirname(dirname(__FILE__)).'/';
require($root_dir."config.inc.php");
require($root_dir."functions.inc.php");

// get list of all expired movies older than a month
$sql = "
	SELECT movie_id, last_updated
	FROM movies
	WHERE status = 'exp'
	AND last_updated <= NOW() - INTERVAL 1 MONTH
";
$movies_res=$mysqli->query($sql) or user_error($sql);

$poster_dir = $root_dir . 'posters/';

if ($movies_res->num_rows >= 1) {
	while ($movies = $movies_res->fetch_assoc()) {
		foreach( glob($poster_dir . $movies['movie_id'] . '-*-*.jpg') as $poster ) {
			@unlink($poster);
		}
	}
}

// Delete all movie records
$sql = "
	DELETE FROM movies
	WHERE status = 'exp'
	AND last_updated <= NOW() - INTERVAL 1 MONTH
";
$mysqli->query($sql) or user_error($sql);

// Delete poster records for those movies
$sql = "
	DELETE FROM posters p
	WHERE NOT EXISTS (SELECT m.movie_id FROM movies m WHERE p.movie_id = m.movie_id)
";
$mysqli->query($sql) or user_error($sql);

// Delete booking records older than 3 months
$sql = "
	DELETE FROM booking_log
	WHERE booked_on <= NOW() - INTERVAL 3 MONTH
";
$mysqli->query($sql) or user_error($sql);


?>
