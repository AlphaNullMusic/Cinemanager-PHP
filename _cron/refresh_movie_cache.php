#!/usr/local/bin/php
<?

$global_root_dir = dirname(dirname(__FILE__)).'/';
require($global_root_dir."global_functions.inc.php");
require($global_root_dir."global_settings.inc.php");

if (!class_exists('db')) { db_pdo(); }
$db = new db;

$sql = "
	SELECT movie_id
	FROM movie_cache
	WHERE session_count > 0
	ORDER BY movie_id
";
$stmt = $db->prepare($sql);
$stmt->execute();
$movies = $stmt->fetchAll(PDO::FETCH_ASSOC);
foreach ($movies as $m) {
	update_movie_cache($m['movie_id']);
}

?>