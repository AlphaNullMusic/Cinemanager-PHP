<?

require('inc/common.inc.php');

// record that this email was opened if this is the first time
if (isset($_GET['n']) && isset($_GET['u']) && is_numeric($_GET['n']) && is_numeric($_GET['u'])) {
	$db = new db;
	logNewsletterOpen($_GET['u'], $_GET['n']);
}

// display gif
header('Content-Type: image/gif');
echo base64_decode('R0lGODlhAQABAJAAAP8AAAAAACH5BAUQAAAALAAAAAABAAEAAAICBAEAOw==');
die;

?>