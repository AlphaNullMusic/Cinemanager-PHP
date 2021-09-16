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


	if (isset($_POST['update-list'])) {
		//$page_list = get_page_list(
		$sql = "UPDATE pages
			SET status='ok'
			WHERE 1";
		$mysqli->query($sql) or die("Gnarly: ".$sql);

		foreach ($_POST as $key => $value) {
			if (substr($key, 0, strlen("hide-")) === "hide-") {
				$name = str_replace("hide-", "", $key);
				$sql = "UPDATE pages
					SET status='hidden'
					WHERE reference='".$mysqli->real_escape_string($name)."'
				";
				$mysqli->query($sql) or die("Gnarly: ".$sql);
			}
		}

		smarty_clear_cache(NULL,NULL,NULL,false,true);
	}

}

?>
<!DOCTYPE HTML>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Manage Custom Pages</title>
    <link href="inc/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="inc/css/dashboard.css" rel="stylesheet">
  </head>
  <body>
  <?php include("inc/header.inc.php");?>
  <div class="container-fluid">
    <div class="row">
		<?php include("inc/nav.inc.php");
			if (check_cinema() && has_permission('edit_pages')) {
				if (isset($_REQUEST['edit']) && !empty($_REQUEST['edit'])) {
					if ($page_count == 0) {?>
						<main role="main" class="col-md-9 ml-sm-auto col-lg-12 pt-3 px-4">
						    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
						        <h1 class="h2">Edit Your Webpages</h1>
						    </div>
							<?php echo check_msg(); ?>
							<p><em>Sorry but the page you are requesting could not be found or you don't have permission to edit it.</em></p>
						</main>
			  <?php } else { ?>
						<main role="main" class="col-md-9 ml-sm-auto col-lg-12 pt-3 px-4">
						    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
								<h1 class="h2">Editing <?php echo $page['title']?></h1>
						    </div>
							<? echo check_msg(); ?>
							<form action="pages.php" method="post" name="page_form">
								<p>Use the boxes below to edit this page. If you are copying and pasting from Microsoft Word, please use the Paste From Word or Paste as Plain Text buttons, otherwise your formatting might get muddled.</p>
								<?php 
								$editor_name = 'content';
								$editor_value = $page['content'];
								include('inc/tiny_mce/load.php');
								?>
								<input type="hidden" name="edit" value="<?php echo $_REQUEST['edit']?>">
							</form>
							<div class="form-group">
								<button name="submit" class="btn btn-success submit" onclick="uploadImagesTinyMCE();">Save This Page</button>
                                                                                &nbsp; or &nbsp;
                                                        	<a href="pages.php" class="btn btn-outline-danger">abandon your changes.</a>
							</div>
						</main>
			  <?php }
				} else { ?>
					<main role="main" class="col-md-9 ml-sm-auto col-lg-12 pt-3 px-4">
						<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
						    <h1 class="h2">Edit Your Webpages</h1>
						</div>
						<?php echo check_msg(); ?>
						<p>Click on a page below to edit it.</p>
				        <?php $sql="
							SELECT 
								page_id, 
								reference, 
								title, 
								status ,
								priority
							FROM pages 
							WHERE status = 'ok' or status = 'hidden' 
							ORDER BY priority ASC, title 
						";
						$page_res = $mysqli->query($sql) or user_error("Gnarly: $sql");
						if ($page_res->num_rows == 0) { ?>
							<em>No pages found.</em>
				  <?php } else { ?>
				<form action="pages.php" method="post" name="page_list_form">
					<table class="table">
						<thead>
							<tr>
								<th scope="col">Title</th>
								<th scope="col">Priority</th>
								<th scope="col"></th>
								<th scope="col">Hidden</th>
								<th scope="col"></th>
							</tr>
						</thead>
						<tbody>
						<?php while ($page_data=$page_res->fetch_assoc()) { ?>
							<tr name="<?php echo $page_data['reference']?>">
								<td scope="row"><?php echo $page_data['title']?></td>
								<td><?php echo $page_data['priority']?></td>
								<td></td>
								<td><div class="form-check">
									<input class="form-check-label" name="hide-<?php echo $page_data['reference']?>" type="checkbox" value="" <?php if ($page_data['status'] == 'hidden') { echo 'checked'; } ?>>
								</div></td>
								<td><a class="btn btn-outline-info btn-sm" href="?edit=<?php echo $page_data['page_id']?>">Edit</a></td>
							</tr>
						<?php } ?>
						</tbody>
					</table>
					<input type="hidden" name="update-list" value="true"></input>
					<button type="submit" class="btn btn-success">Save Changes</button>
				</form>
				<?php } ?>
					</main>
		  <?php } 
			} else { ?>
				<main role="main" class="col-md-9 ml-sm-auto col-lg-12 pt-3 px-4">
					<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
						 <h1 class="h2">Edit Your Webpages</h1>
					</div>
					<p><?php check_notice("Either you are not logged in or you do not have permission to use this feature.") ?></p>
					<p>On this page you can edit the content of custom pages on your website.</p>
	  <?php } ?>
		<?php include("inc/footer.inc.php") ?>
	</body>
</html>
