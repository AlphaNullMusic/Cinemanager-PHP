<?php

session_start();
ini_set('display_errors',1); 
error_reporting(E_ALL);

$root_dir = dirname(dirname(dirname(dirname(__FILE__)))).'/';
$use_pdo = true;
require($root_dir."config.inc.php");
require($root_dir."functions.inc.php");

function logNewsletterOpen($u, $n) {
	global $db;
	$sql = "
		SELECT id
		FROM newsletter_user_log
		WHERE user_id = ?
			AND newsletter_id = ?
			AND action = 'open'
	";
	$stmt = $db->prepare($sql);
	$stmt->execute(array($u,$n));
	$data = $stmt->fetch(PDO::FETCH_ASSOC);
	$stmt = NULL;
	if (!isset($data['id']) || empty($data['id'])) {
		$sql = "
			INSERT INTO newsletter_user_log
			SET user_id = ?,
				newsletter_id = ?,
				action = 'open',
				time = NOW()
		";
		$stmt = $db->prepare($sql);
		$stmt->execute(array($u,$n));
		$stmt = NULL;
		$sql = "
			UPDATE newsletter_log
			SET status = 'opened'
			WHERE user_id = ?
				AND newsletter_id = ?
		";
		$stmt = $db->prepare($sql);
		$stmt->execute(array($u,$n));
		$stmt = NULL;
	}
}

?>