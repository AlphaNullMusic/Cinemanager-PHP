<?php
require("inc/manage.inc.php");

if (check_cinema() && has_permission('sessions')) {

	// Delete preset
	if (isset($_GET['delete'])) {
		// Delete label
		$sql = "DELETE FROM classifications WHERE classification_id = '{$_GET['delete']}'";
		$mysqli->query($sql);
		// Clear Smarty cache
		smarty_clear_cache(NULL,NULL,NULL,false,true);
		// Reload
		header("Location: classifications.php?conf=Classification+deleted+successfully.");
		exit;
	} 
	
	// Editing presets
	$classifications = array();
	$sql = "
		SELECT 
			classification_id, 
			classification,
			class_explanation,
			priority
		FROM classifications
		ORDER BY priority
	";
	$res = query($sql);
	while ($data = $res->fetch_assoc()) {
		$classifications[$data['classification_id']] = $data['classification'];
	}
	
	if (!isset($_POST['new']) && !isset($_POST['classification_id'])) {
		$_REQUEST['new'] = '';
	}
	
	// Save labels
	if (isset($_POST['submit'])) {
		// Check vars
		if (!isset($_POST['classification']) || strlen(preg_replace('/[^a-z]+/i','',$_POST['classification']))==0) {
			$er = "A classification name is required.";
		}
		if (!isset($er)) {
			$sql = ($_POST['edit'] == 'new') ? "INSERT INTO " : "UPDATE " ;
			// Save name
			$sql .= "
				classifications
				SET classification = '".$mysqli->real_escape_string($_POST['classification'])."',
				class_explanation = '".$mysqli->real_escape_string($_POST['class_explanation'])."'
			";
			$sql .= ($_POST['edit'] == 'new') ? "" : "WHERE classification_id = '{$_POST['edit']}' " ;
			$mysqli->query($sql);
			$classification_id = ($_POST['edit'] != 'new') ? $_POST['edit'] : $mysqli->insert_id;
			// Clear Smarty cache
			smarty_clear_cache(NULL,NULL,NULL,false,true);
		}
		// Reload
		$location = "classifications.php?";
		$location .= ($_POST['redir'] == 'edit' || isset($er)) ? "edit={$_POST['edit']}&" : "" ;
		if (isset($er)) {
			$location .= "er=$er";
		} else {
			$location .= "conf=Classification+saved+successfully.";
		}
		// Clear smarty cache
		smarty_clear_cache(NULL,NULL,NULL,false,true);
		// Reload
		header("Location: $location");
		exit;
	}

	// Get specific details for editing
	if (isset($_REQUEST['edit'])) {
		$values = array();
		$sql = "
			SELECT c.classification_id, c.classification, c.class_explanation
			FROM classifications c
			WHERE c.classification_id = '".$mysqli->real_escape_string($_REQUEST['edit'])."'
		";
		$res = $mysqli->query($sql);
		while ($data = $res->fetch_assoc()) {
			if (!isset($values['classification']) && isset($data['classification'])) {
				$values['classification'] = $data['classification'];
				$values['class_explanation'] = (isset($data['class_explanation'])) ? $data['class_explanation'] : '';
			}
		}
	}
}


?>
<!DOCTYPE HTML>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="includes/generic.js" type="text/javascript"></script>
    <title><?php echo $title_prefix?>Manage Your Classifications</title>
    <link href="inc/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="inc/css/dashboard.css" rel="stylesheet">
  </head>
  <body>
  <?php include("inc/header.inc.php");?>
  <div class="container-fluid">
    <div class="row">
      <?php include("inc/nav.inc.php");
		if (check_cinema() && has_permission('sessions')) { ?>
				<main role="main" class="col-md-9 ml-sm-auto col-lg-10 pt-3 px-4">
					<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
						<h1 class="h2">Manage Classifications</h1>
					</div>
					<p>
						Fill in the boxes below to modify your classifications.<br>
						Click a link on the right to edit different classifications or add new ones.
					</p>
					<?php echo check_msg(); ?>
					<h2><?php echo (isset($_REQUEST['edit']))?'Edit this':'Add a new'?> classification</h2>
					<table border="0" cellpadding="0" cellspacing="0">
						<tr>
							<td valign="top">
								<form name="form" method="post" action="<?=$_SERVER['PHP_SELF']?>">
									<table border="0" cellpadding="1" cellspacing="0">
										<tr>
											<td>
												<label for="classification"><strong>Classification<strong></label>
												<div class="input-group mb-3">
													<input name="classification" id="classification" class="form-control" type="text" value="<?php echo (isset($values['classification']))?$values['classification']:''?>">
												</div>
											</td>
										</tr>
										<tr>
											<td>
												<label for="class_explanation"><strong>Explanation<strong></label>
												<div class="input-group mb-3">
													<input name="class_explanation" id="class_explanation" class="form-control" type="text" value="<?php echo (isset($values['class_explanation']))?$values['class_explanation']:''?>">
												</div>
											</td>
										</tr>
									</table>
									<table border="0" cellpadding="1" cellspacing="0">
										<tr>
											<td>&nbsp;</td>
											<td>&nbsp;</td>
											<td>
												<br>
												<input name="submit" class="btn btn-success submit" type="submit" value="Save This Classification">
											</td>
										</tr>
								  <?php if ($_REQUEST['edit'] != 'new') { ?>
										<tr>
											<td>&nbsp;</td>
											<td>&nbsp;&nbsp;</td>
											<td>
												<br>
												<a href="?delete=<?php echo $_REQUEST['edit']?>" onClick="return confirmDelete();" class="btn btn-outline-danger">Delete This Classification</a>
											</td>
										</tr>
								  <?php } ?>
									</table>
									<input type="hidden" name="edit" value="<?php echo (isset($_REQUEST['edit'])) ? $_REQUEST['edit'] : 'new'?>">
								</form>
							</td>
						</tr>
						<tr>
							<td valign="top">
								<hr>
							</td>
						</tr>
						<tr>
							<td valign="top">
								<h2><?php echo (isset($_REQUEST['edit']))?'Edit a different':'Edit an existing'?> classification</h2>
										<ul>
									<?php
										$sql = "
											SELECT url
											FROM settings
											WHERE id = 1
											LIMIT 1
										";
										$res = $mysqli->query($sql) or die($mysqli->error());
										if ($res->num_rows == 1) {
											$primary_domain = $res->fetch_assoc()['url'];
										}
										foreach ($classifications as $id => $name) { ?>
											<li><a href="?edit=<?php echo $id?>"><?php echo $name?></a></li>
								  <?php } ?>
										</ul>
											<br><a href="?new" class="btn btn-sm btn-outline-info">Add New Classification</a>
							</td>
						</tr>
					</table>		
  <?php } else { ?>
			<main role="main" class="col-md-9 ml-sm-auto col-lg-10 pt-3 px-4">
				<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
					<h1 class="h2">Classifications</h1>
				</div>
				<p><?php check_notice("Either you are not logged in or you do not have permission to use this feature.") ?></p>
  <?php } ?>
<?php include("inc/footer.inc.php") ?> 
</body>
</html>
