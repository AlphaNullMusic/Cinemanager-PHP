<?php

include("inc/manage.inc.php");

if (check_cinema()) { // && check_admin()
	// Save Changes
	if (isset($_POST['submit'])) {
		$fn = dirname(dirname(__FILE__)).'/config.inc.php';
		if (file_put_contents($fn,$_POST['config_content'])) {
			$redir = $_SERVER['PHP_SELF'] . "?conf=Successfully+saved+config+file.";
			header("Location: $redir");
		}
	} else {
		$fn = dirname(dirname(__FILE__)).'/config.inc.php';
		$line_count = count(file($fn)) + 10;
		$file = fopen($fn, "a+");
		$size = filesize($fn);

		$text = fread($file, $size);
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
	  		if (check_cinema()) { ?>
	    			<main role="main" class="col-md-9 ml-sm-auto col-lg-12 pt-3 px-4">
	      				<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
                				<h1 class="h2">Manage Settings</h1>
              				</div>
					<?php echo check_msg(); ?>
	      				<form name="form" method="post" action="<?php echo $_SERVER['PHP_SELF']?>">
                			<?php /*foreach ($config as $key => $value) { 
						if ($key != "safe_mime_types" && $key != "poster_sizes" ) {?>
                    				<div class="form-group">
							<label for="<?php echo $key;?>"><?php echo $key; ?></label>
							<input 
								name="<?php echo $key;?>" 
								class="form-control" 
								value="<?php echo $value;?>"
							>
						</div>
                  				<?php }
					} */?>
					<div class="form-group">
						<label for="config_content">config.inc.php</label>
						<textarea name="config_content" rows="<?php echo $line_count;?>" class="form-control"><?php echo $text;?></textarea>
					</div>
					<div class="form-group">
						<input name="submit" type="submit" class="btn btn-success submit" value="Save Changes">
              				</div>
					</form>

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
