<?php
require("inc/manage.inc.php");

global $mysqli;

if (check_cinema() && has_permission('edit_pages')) {
	// Get page info
	if (isset($_REQUEST['edit']) && !empty($_REQUEST['edit'])) { 
		$sql = "
			SELECT 
				page_id, 
				reference, 
				title, 
				content, 
				status
			FROM pages 
			WHERE 
				status = 'ok' 
				AND page_id = '".$mysqli->real_escape_string($_REQUEST['edit'])."'
			LIMIT 1
		";
		$page_res = $mysqli->query($sql) or user_error("Gnarly: $sql");
		$page_count = $page_res->num_rows;
		$page = $page_res->fetch_assoc();
	}

	// Save page
	if (isset($_POST['content'])) {
		$sql = "
			UPDATE pages
			SET content = '".$mysqli->real_escape_string($_POST['content'])."'
			WHERE status = 'ok' 
				AND page_id = '".$mysqli->real_escape_string($_POST['edit'])."'
			LIMIT 1
		";
		$mysqli->query($sql) or user_error("Gnarly: $sql");
		smarty_clear_cache(NULL,$page['reference']);
		smarty_clear_cache(NULL,'homepage');
		if (stristr($page['reference'], 'global')) {
			smarty_clear_cache();
		}
		header('Location: pages.php?conf='.$page['title'].' page saved successfully.');
		exit;
	}
	
}

?>
<!DOCTYPE HTML>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="inc/generic.js" type="text/javascript"></script>
    <title>Cinemanager: a Specialised Content Management System for New Zealand Cinemas</title>
    <link href="inc/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="inc/css/dashboard.css" rel="stylesheet">
    <link href="inc/quill/quill.snow.css" rel="stylesheet" type="text/css">
  </head>
  <body>
  <?php include("inc/header.inc.php");?>
  <div class="container-fluid">
    <div class="row">
		<?php include("inc/nav.inc.php");
			if (check_cinema() && has_permission('edit_pages')) {
				if (isset($_REQUEST['edit']) && !empty($_REQUEST['edit'])) {
					if ($page_count == 0) {?>
						<main role="main" class="col-md-9 ml-sm-auto col-lg-10 pt-3 px-4">
						    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
						        <h1 class="h2">Edit Your Webpages</h1>
						    </div>
							<?php confirm() ?>
							<p><em>Sorry but the page you are requesting could not be found or you don't have permission to edit it.</em></p>
						</main>
			  <?php } else { ?>
						<main role="main" class="col-md-9 ml-sm-auto col-lg-10 pt-3 px-4">
						    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
								<h1 class="h2">Editing <?php echo $page['title']?></h1>
						    </div>
							<? confirm() ?>
							<form action="pages.php" method="post" name="page_form">
								<p>Use the boxes below to edit this page. If you are copying and pasting from Microsoft Word, please use the Paste From Word or Paste as Plain Text buttons, otherwise your formatting might get muddled.</p>
								<?php 
								$tiny_mce_name = 'content';
								$tiny_mce_value = $page['content'];
								//include('inc/tiny_mce/load.php');
								include('inc/quill/load.php');
								?>
								<input type="hidden" name="edit" value="<?php echo $_REQUEST['edit']?>">
								<p><input type="submit" name="submit" class="submit" value="Save This Page" /> &nbsp; &nbsp; or <a href="pages.php">abandon</a> your changes.</p>
							</form>
						</main>
			  <?php }
				} else { ?>
					<main role="main" class="col-md-9 ml-sm-auto col-lg-10 pt-3 px-4">
						<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
						    <h1 class="h2">Edit Your Webpages</h1>
						</div>
						<?php confirm() ?>
						<p>Click on a page below to edit it.</p>
				        <?php $sql="
							SELECT 
								page_id, 
								reference, 
								title, 
								status 
							FROM pages 
							WHERE status = 'ok' 
							ORDER BY title 
						";
						$page_res = $mysqli->query($sql) or user_error("Gnarly: $sql");
						if ($page_res->num_rows == 0) { ?>
							<em>No pages found.</em>
				  <?php } else {
							echo "<ul>";
							while ($page_data=$page_res->fetch_assoc()) { ?>
								<li><a href="?edit=<?php echo $page_data['page_id']?>"><?php echo $page_data['title']?></a></li>
					  <?php }
							echo "</ul>";
						} ?>
					</main>
		  <?php } 
			} else { ?>
				<main role="main" class="col-md-9 ml-sm-auto col-lg-10 pt-3 px-4">
					<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
						 <h1 class="h2">Edit Your Webpages</h1>
					</div>
					<p><?php check_notice("Either you are not logged in or you do not have permission to use this feature.") ?></p>
					<p>On this page you can edit the content of any pages on your website. If you are a cinema operator and would like more information on any of our services, please don't hesitate to <a href="contact.php">contact us</a>.</p>
	  <?php } ?>
		<?php include("inc/footer.inc.php") ?>
	</body>
</html>
