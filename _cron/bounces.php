<?

ini_set('display_errors',1); 
error_reporting(E_ALL);

$global_root_dir = dirname(dirname(__FILE__)).'/';
require($global_root_dir."global_settings.inc.php");
require($global_root_dir."global_functions.inc.php");

$limit = 500;
$mail_dir = dirname($global_root_dir).'/mail/moviemanager.co.nz/bounces/new';

if ($handle = opendir($mail_dir)) {
	$n = 0;
	while (false !== ($entry = readdir($handle))) {
		if ($n < $limit) {
			if ($entry != "." && $entry != "..") {
				$message_url = $mail_dir . '/' . $entry;
				$message_handle = fopen($message_url, 'r');
				$message = fread($message_handle, 3000);
				fclose($message_handle);
				// Find the mm token
				preg_match('/X-MovieManagerToken: ?([a-z0-9]+)/im', $message, $matches);
				if ($matches[1]) {
					$token = $matches[1];
					// Mark the message as bounced
					$sql = "
						UPDATE newsletter_log
						SET status = 'bounced'
						WHERE token = '$token'
					";
					mysql_query($sql) or die(mysql_error().$sql);
				}
				// Delete message
				unlink($message_url);
			}
		}
		$n++;
	}
	closedir($handle);
}

// Unsubscribe users who have bounced too many times
/*
$max_bounces = 3;
$max_days = 30;
$sql = "
	SELECT nl.user_id, COUNT(nl.newsletter_id) AS bounces, GROUP_CONCAT(DISTINCT un.cinema_id) AS cinema_ids
	FROM newsletter_log nl
	INNER JOIN user_newsletters un
		ON un.user_id = nl.user_id
	WHERE nl.user_id = nl.user_id
		AND nl.status = 'bounced'
		AND nl.timestamp >= DATE_SUB(NOW(), INTERVAL $max_days DAY)
	GROUP BY nl.user_id
	HAVING bounces >= $max_bounces
";
$res = mysql_query($sql) or die(mysql_error().$sql);
while ($data = mysql_fetch_assoc($res)) {
	foreach (explode(',', $data['cinema_ids']) as $cinema_id) {
		$sql = "
			INSERT INTO user_newsletters_log
			SET user_id = '{$data['user_id']}',
				cinema_id = '$cinema_id',
				action = 'bounce',
				timestamp = NOW()
		";
		mysql_query($sql) or die(mysql_error().$sql);
	}
	$sql = "
		DELETE FROM user_newsletters
		WHERE user_id = '{$data['user_id']}'
	";
	mysql_query($sql) or die(mysql_error().$sql);
}
*/

?>