<?php

require('inc/common.inc.php');

//
// process link
//

if (isset($_GET['l']) && is_numeric($_GET['l'])) {
	$db = new db;
	
	// get the link details
	$sql = "
		SELECT newsletter_id, url
		FROM newsletter_links
		WHERE link_id = ?
	";
	$stmt = $db->prepare($sql);
	$stmt->execute(array($_GET['l']));
	$data = $stmt->fetch(PDO::FETCH_ASSOC);
	$stmt = NULL;
	
	// log the activity
	if (isset($_GET['u']) && is_numeric($_GET['u'])) {
		logNewsletterOpen($_GET['u'], $data['newsletter_id']);
		$sql = "
			INSERT INTO newsletter_user_log
			SET user_id = ?,
				newsletter_id = ?,
				action = 'click',
				time = NOW(),
				url = ?
		";
		$stmt = $db->prepare($sql);
		$stmt->execute(array($_GET['u'],$data['newsletter_id'],$data['url']));
		$stmt = NULL;
	}
	
	// redirect
	header("HTTP/1.1 301 Moved Permanently");
	header("Location: ".htmlspecialchars_decode($data['url']));
	
}

//
// process unsubscribe
//

elseif (isset($_GET['u']) && isset($_GET['un'])) {
	if (encode($_GET['u'])==$_GET['un']) {
		$db = new db;
		// log the activity
		if (!empty($_GET['n'])) {
			logNewsletterOpen($_GET['u'], $_GET['n']);
			$sql = "
				INSERT INTO newsletter_user_log
				SET user_id = ?,
					newsletter_id = ?,
					action = 'unsubscribe',
					time = NOW()
			";
			$stmt = $db->prepare($sql);
			$stmt->execute(array($_GET['u'],$_GET['n']));
			$stmt = NULL;
		}
		
		// unsubscribe
		$user_id = $_GET['u'];
		$sql = "
			UPDATE users 
			SET status='deleted' 
			WHERE user_id=? 
			LIMIT 1
		";
		$stmt = $db->prepare($sql);
		$stmt->execute(array($user_id));
		$stmt = NULL;

		// get cinema name for confirmation
		$sql = "
			SELECT name, url
			FROM settings 
			WHERE id=? 
			LIMIT 1
		";
		$stmt = $db->prepare($sql);
		$stmt->execute(array('1'));
		$data = $stmt->fetch(PDO::FETCH_ASSOC);
		$stmt = NULL;
		
		// report back
		echo "<!DOCTYPE HTML PUBLIC '-//W3C//DTD HTML 4.01 Transitional//EN' 'http://www.w3.org/TR/html4/loose.dtd'><html><head><meta http-equiv='Content-Type' content='text/html; charset=UTF-8'><title>{$data['cinema_name']} Email Newsletter</title><style type='text/css'><!-- body { font-family: 'Lucida Grande', Verdana, Arial, Helvetica, sans-serif; font-size: 16px; margin: 30px 30px 30px 30px; } --></style></head><body>";
		echo "&#10003; You have been un-subscribed from the <a href='{$data['url']}'>{$data['name']}</a> email list.";
		echo "</body></html>";
		exit;
		
	}
}

?>
