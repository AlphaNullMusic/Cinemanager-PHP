<?php

include("inc/manage.inc.php");

if (check_cinema() && check_admin()) {

       /**
	 * Slightly modified version of http://www.geekality.net/2011/05/28/php-tail-tackling-large-files/
	 * @author Torleif Berger, Lorenzo Stanco
	 * @link http://stackoverflow.com/a/15025877/995958
	 * @license http://creativecommons.org/licenses/by/3.0/
	 */
	function tailCustom($filepath, $lines = 50, $adaptive = true) {
		// Open file
		$f = @fopen($filepath, "rb");
		if ($f === false) return false;
		// Sets buffer size, according to the number of lines to retrieve.
		// This gives a performance boost when reading a few lines from the file.
		if (!$adaptive) $buffer = 4096;
		else $buffer = ($lines < 2 ? 64 : ($lines < 10 ? 512 : 4096));
		// Jump to last character
		fseek($f, -1, SEEK_END);
		// Read it and adjust line number if necessary
		// (Otherwise the result would be wrong if file doesn't end with a blank line)
		if (fread($f, 1) != "\n") $lines -= 1;
		
		// Start reading
		$output = '';
		$chunk = '';
		// While we would like more
		while (ftell($f) > 0 && $lines >= 0) {
			// Figure out how far back we should jump
			$seek = min(ftell($f), $buffer);
			// Do the jump (backwards, relative to where we are)
			fseek($f, -$seek, SEEK_CUR);
			// Read a chunk and prepend it to our output
			$output = ($chunk = fread($f, $seek)) . $output;
			// Jump back to where we started reading
			fseek($f, -mb_strlen($chunk, '8bit'), SEEK_CUR);
			// Decrease our line counter
			$lines -= substr_count($chunk, "\n");
		}
		// While we have too many lines
		// (Because of buffer size we might have read too many)
		while ($lines++ < 0) {
			// Find first newline and remove all text before that
			$output = substr($output, strpos($output, "\n") + 1);
		}
		// Close file and return
		fclose($f);
		return trim($output); 
	}
	
	if (
		isset($_GET['error-web']) ||
		isset($_GET['error-manage']) ||
		isset($_GET['error-mysql']) ||
		isset($_GET['error-posters'])
	) {
		if (isset($_GET['error-web'])) {
			$log_name = 'error-web.log';
		} else if (isset($_GET['error-manage'])) {
                        $log_name = 'error-manage.log';
                } else if (isset($_GET['error-mysql'])) {
                        $log_name = 'error-mysql.log';
                } else if (isset($_GET['error-posters'])) {
                        $log_name = 'error-posters.log';
                }
		$fn = "/var/www/logs/".$log_name;
		$file = fopen($fn,"r");
		$size = filesize($fn);
		if ($size > 0) {
			$text = fread($file,$size);
			if (trim($text) == '') {
				$text = "No file content";
			}
		} else {
			$text = "No file content";
		}
		//$text = tailCustom($fn);
		fclose($file);
	}
}

?>

<!DOCTYPE HTML>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Manage Your Cinema Website Settings</title>
		<link href="inc/css/bootstrap.min.css" rel="stylesheet" type="text/css">
		<link href="inc/css/dashboard.css" rel="stylesheet">
  	</head>
  	<body>
  	<?php include("inc/header.inc.php");?>
    		<div class="container-fluid">
      			<div class="row">
			<?php include("inc/nav.inc.php");
	  		if (check_cinema() && check_admin()) { ?>
	    			<main role="main" class="col-md-9 ml-sm-auto col-lg-12 pt-3 px-4">
	      				<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
                				<h1 class="h2">View Logs</h1>
              				</div>
					<?php echo check_msg(); ?>

					<div class="form-group">
						<button class="btn btn-primary" style="margin-bottom:15px;" onclick="document.location = '?error-web';">Web Errors</button>
                                              	<button class="btn btn-primary" style="margin-bottom:15px;" onclick="document.location = '?error-manage';">Manage Errors</button>
                                                <button class="btn btn-primary" style="margin-bottom:15px;" onclick="document.location = '?error-mysql';">MySQL Errors</button>
                                                <button class="btn btn-primary" style="margin-bottom:15px;" onclick="document.location = '?error-posters';">Poster Errors</button>

					</div>
					
					<?php if (isset($_GET['error-web']) || isset($_GET['error-manage']) || isset($_GET['error-mysql']) || isset($_GET['error-posters'])) { ?>
						<p><?php echo $log_name;?></p>
						<pre><?php echo $text;?></pre>
					<?php } ?>
			<?php } else { ?>
	   			<main role="main" class="col-md-9 ml-sm-auto col-lg-10 pt-3 px-4">
	      				<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
						<h1 class="h2">Manage Settings</h1>
	      				</div>
	      				<p><?php check_notice("Either you are not logged in or you do not have permission to use this feature.") ?></p>
	      				<p>On this page you can edit the settings of your cinema website.</p>
			<?php } ?>
      		<?php include("inc/footer.inc.php") ?>
  	</body>
</html>
