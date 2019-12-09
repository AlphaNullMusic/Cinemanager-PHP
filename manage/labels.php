<?php
require("inc/manage.inc.php");

if (check_cinema() && has_permission('sessions')) {

	//Delete preset
	if (isset($_GET['delete'])) {
		// Delete label
		$sql = "DELETE FROM session_labels WHERE label_id = '{$_GET['delete']}'";
		$mysqli->query($sql);
		// Clear Smarty cache
		smarty_clear_cache(NULL,NULL,NULL,false,true);
		// Reload
		header("Location: labels.php?conf=Label deleted successfully.");
		exit;
	} 
	
	//apply session preset changes
	elseif (isset($_POST['action']) && $_POST['action']=='apply_presets' && isset($_POST['session_labels'])) {
		// Validation
		if (!isset($_POST['s']) || !is_array($_POST['s'])) {
			$er = "No sessions were selected.";
		}
		if (!isset($er)) {
			$sp = new manage_sessions();
			foreach ($_POST['s'] as $s) {
				if ($_POST['session_labels']=='default') {
					//$sp->set_default_prices($s);
					$sp->set_label($s);
				} else {
					//$sp->set_custom_prices($s,$_POST['session_labels']);
					$sp->set_label($s,$_POST['session_labels']);
				}
			} 
		}
		// Clear Smarty cache
		smarty_clear_cache($_SESSION['cinema_data']['cinema_id']);
		// Reload
		$location = "labels.php?";
		// Add movie_id_array
		if (isset($_REQUEST['movie_id_array'])) {
			foreach ($_REQUEST['movie_id_array'] as $m) {
				$location .= "movie_id_array[]=$m&";
			}
		}
		if (isset($er)) {
			$location .= "er=$er";
		} else {
			$location .= "conf=Labels updated successfully.";
		}
		// Clear smarty cache
		smarty_clear_cache(NULL,NULL,NULL,false,true);
		// Reload
		header("Location: $location");
		exit;
	}
	
	// Editing presets
	elseif (isset($_REQUEST['edit'])) {
		// Get labels
		$presets = array();
		$sql = "
			SELECT label_id, name
			FROM session_labels
			ORDER BY name
		";
		$res = query($sql);
		while ($data = $res->fetch_assoc()) {
			$presets[$data['label_id']] = $data['name'];
		}

		// Save labels
		if (isset($_POST['submit'])) {
			// Check vars
			if (!isset($_POST['name']) || strlen(preg_replace('/[^a-z]+/i','',$_POST['name']))==0) {
				$er = "A label name is required.";
			}
			if (!isset($er)) {
				$sql = ($_POST['edit'] == 'new') ? "INSERT INTO " : "UPDATE " ;
				// Save name
				$sql .= "
					session_labels
					SET name = '".addslashes($_POST['name'])."'
				";
				$sql .= ($_POST['edit'] == 'new') ? "" : "WHERE label_id = '{$_POST['edit']}' " ;
				$mysqli->query($sql);
				$label_id = ($_POST['edit'] != 'new') ? $_POST['edit'] : $mysqli->insert_id;
				//clear out old prices
				/*$sql = "
					DELETE FROM session_preset
					WHERE session_preset_group_id = '{$_POST['edit']}'
				";
				$mysqli->query($sql);
				//enter new prices (if custom prices are selected)
				if (isset($_POST['prices']) && $_POST['prices'] == 'custom') {
					foreach ($types as $id => $name) {
						if (isset($_POST['price'.$id]) && $_POST['price'.$id]>0) {
							$sql = "
								INSERT INTO session_preset
								SET session_preset_group_id = '$session_preset_group_id',
								session_type_id = '$id',
								price = '{$_POST['price'.$id]}'
							";
							$mysqli->query($sql);
						}
					}
				}
				//update pricing of sessions using this preset
				$sp=new manage_sessions();
				$sp->cinema_id = $_SESSION['cinema_data']['cinema_id'];
				$sp->prices = false;
				$sp->set_custom_prices_type($_POST['edit']);*/
				// Clear Smarty cache
				smarty_clear_cache();
			}
			// Reload
			$location = "labels.php?";
			$location .= ($_POST['redir'] == 'edit' || isset($er)) ? "edit={$_POST['edit']}&" : "" ;
			if (isset($er)) {
				$location .= "er=$er";
			} else {
				$location .= "conf=Label saved successfully.";
			}
			// Clear smarty cache
			smarty_clear_cache(NULL,NULL,NULL,false,true);
			// Reload
			header("Location: $location");
			exit;
		}
		
		// Get specific details for editing
		if ($_REQUEST['edit'] != 'new') {
			$values = array();
			$sql = "
				SELECT sl.label_id, sl.name
				FROM session_labels sl
				WHERE sl.label_id = '{$_REQUEST['edit']}'
			";
			$res = $mysqli->query($sql);
			while ($data = $res->fetch_assoc()) {
				if (!isset($values['name']) && isset($data['name'])) {
					$values['name'] = $data['name'];
					$values['comments'] = (isset($data['comments'])) ? $data['comments'] : '';
				}
			}
			$values['show_prices'] = (isset($values['prices']) && $_REQUEST['edit'] != 'new') ? true : false;
		}
	}

	// Get default array of session data
	else {
		$movie_sessions = get_movie_sessions(
			(isset($_REQUEST['movie_id_array']))?$_REQUEST['movie_id_array']:NULL,
			(isset($_REQUEST['date_array']))?$_REQUEST['date_array']:NULL,
			false,
			'%Y-%m-%d', 
			'%l:%i%p', 
			true
		);
	}

}


?>
<!DOCTYPE HTML>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Manage Your Cinema Ticket Prices and Session Labels</title>
    <link href="inc/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="inc/css/dashboard.css" rel="stylesheet">
  </head>
  <body>
  <?php include("inc/header.inc.php");?>
  <div class="container-fluid">
    <div class="row">
      <?php include("inc/nav.inc.php");
		if (check_cinema() && has_permission('sessions')) {
			if (isset($_REQUEST['edit'])) { ?>
				<main role="main" class="col-md-9 ml-sm-auto col-lg-10 pt-3 px-4">
					<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
						<h1 class="h2">Manage Labels</h1>
						<div class="btn-toolbar mb-2 mb-md-0">
							<div class="btn-group mr-2">
								<?php button_1("< Back To Label List", "labels.php", "back", "right"); ?>
							</div>
						</div>
					</div>
					<p>
						Fill in the boxes below to modify your session labels.<br>
						Click a link on the right to edit different labels or add new ones.
					</p>
					<?php echo check_msg();
					if (!empty($_GET['edit'])) { ?>
						<h2><?php echo ($_GET['edit'] == 'new')?'Add a new':'Edit this'?> label</h2>
			  <?php } ?>
					<table border="0" cellpadding="0" cellspacing="0">
						<tr>
							<td valign="top">
								<form name="form" method="post" action="<?=$_SERVER['PHP_SELF']?>">
									<table border="0" cellpadding="1" cellspacing="0">
										<tr>
											<td>
												<label for="name"><strong>Name<strong></label>
												<div class="input-group mb-3">
													<input name="name" id="name" class="form-control" type="text" value="<?php echo (isset($values['name']))?$values['name']:''?>">
												</div>
											</td>
										</tr>
									</table>
									<table border="0" cellpadding="1" cellspacing="0">
										<tr>
											<td valign="top">
												<br>
												<strong>Where to next?</strong>
											</td>
											<td>&nbsp;&nbsp;</td>
											<td>
												<br>
												<input name="redir" type="radio" id="redir_1" value="edit" checked>
												<label for="redir_1">Stay on this page</label><br>
												<input name="redir" type="radio" id="redir_2" value="apply">
												<label for="redir_2">View sessions and apply labels</label><br>
											</td>
										</tr>
										<tr>
											<td>&nbsp;</td>
											<td>&nbsp;</td>
											<td>
												<br>
												<input name="submit" class="btn btn-success submit" type="submit" value="Save This Label">
											</td>
										</tr>
								  <?php if ($_REQUEST['edit'] != 'new') { ?>
										<tr>
											<td>&nbsp;</td>
											<td>&nbsp;&nbsp;</td>
											<td>
												<br>
												<a href="?delete=<?php echo $_REQUEST['edit']?>" onClick="return confirmDelete();" class="btn btn-outline-danger">Delete This Label</a>
											</td>
										</tr>
								  <?php } ?>
									</table>
									<input type="hidden" name="edit" value="<?php echo $_REQUEST['edit']?>">
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
								<h2><?php echo ($_GET['edit'] == 'new')?'Edit an existing':'Edit a different'?> label</h2>
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
										foreach ($presets as $id => $name) { ?>
											<li><a href="?edit=<?php echo $id?>"><?php echo $name?></a></li>
								  <?php } ?>
										</ul>
											<br><a href="?edit=new" class="btn btn-sm btn-outline-info">Add New Label</a>
							</td>
						</tr>
					</table>		
	  <?php } else { ?>
				<main role="main" class="col-md-9 ml-sm-auto col-lg-10 pt-3 px-4">
					<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
						<h1 class="h2">Label Your Sessions</h1>
						<?php button_1("Add or Edit Labels","?edit=new","back","right") ?>
					</div>
					<form name="form" method="post" action="<?php echo $_SERVER['PHP_SELF']?>"> 
						<?php echo check_msg()?>
						<p>You can apply a label to any of your session times. Labels can be used to identify special screenings, discounted sessions, pricing changes and so on.</p>
						<ul>
							<li>Sessions that already have labels are highlighted in yellow.</li>
							<li>Hold your mouse over hilighted sessions to see the name of the label that has applied.</li>
							<li>Click a movie title to select all sessions for that movie (click again to un-select).</li>
							<li>Click a session date to select all sessions with that date (click again to un-select).</li>
							<li><a href="?edit=new">Click here to add or edit your labels.</a></li>
						</ul>
				  <?php if (isset($movie_sessions['array_data'])) { ?>
							<h5>1) Select the sessions you would like to label...</h5>
							<table class="table table-striped table-sm">
							<?php 
								$max_sessions_per_day=$movie_sessions['array_data']['max_sessions_per_day'];
								$colspan=$max_sessions_per_day*2+2;
								foreach ($movie_sessions['movies'] as $movie_id => $movie_data) { 
									$movie_session_summary='';
									foreach ($movie_data['movie_data']['session_summary'] as $value) {
										$movie_session_summary.=' s'.$value;
									}
									if (isset($started)) { ?>
										<tr><td>&nbsp;</td>
							  <?php } else {
										$started = true;
									} ?>
									<thead>
										<tr>
											<td colspan="<?php echo $colspan?>">
												<a href="javascript:checkMultiple('<?php echo trim($movie_session_summary)?>');" class="h5">
													<?php echo $movie_data['movie_data']['title']?>
												</a>
											</td>
										</tr>
									</thead>
									<tbody>
								<?php 
									unset($sessions_started);
									foreach ($movie_data['sessions'] as $session_date => $session_data) { 
										// Create list of sessions for javascript select all function
										$select_all_list='';
										foreach ($session_data as $session_id => $sessions) {
											$select_all_list.=' s'.$session_id;
										} ?>
										<tr>
											<td nowrap width="18">&nbsp; &nbsp; &nbsp;</td>
											<td nowrap align="right">
												<a href="javascript:checkMultiple('<?php echo trim($select_all_list)?>');">
													<?php echo date('D j M',strtotime($session_date))?>
												</a>
											</td>
									  <?php foreach ($session_data as $session_id => $sessions) { 
												$checkbox_id='s'.$session_id; ?>
												<td>&nbsp;</td>
												<td nowrap>
													<input 
														type="checkbox" 
														name="s[]" 
														value="<?php echo $session_id?>" 
														id="<?php echo $checkbox_id?>" 
														class="inline_input"
													>
													<label for="<?php echo $checkbox_id?>"<?php echo ($sessions['label']) ? " class=\"custom_session\" title=\"{$sessions['label']}\"" : "";?>>
														<?php echo ($sessions['label']) ? "<mark>&nbsp;".$sessions['time']."</mark>" : "&nbsp;".$sessions['time'];?>
													</label>
												</td>
									  <?php } ?>
										</tr>
							  <?php } 
								} ?>
									</tbody>
								</table>
								<br><br>
								<h5>2) Select the label to apply...</h5>
								<?php $res=$mysqli->query("SELECT label_id,name FROM session_labels ORDER BY name");
								while ($data=$res->fetch_assoc()) { ?>
									<input 
										name="session_labels" 
										type="radio" 
										class="inline_input" 
										value="<?php echo $data['label_id']?>" 
										id="sl<?php echo $data['label_id']?>"
									> 
									<label for="sl<?php echo $data['label_id']?>">
										<?php echo $data['name']?>
									</label>
									<br>
						  <?php } ?>
								<input 
									name="session_labels" 
									type="radio" 
									class="inline_input" 
									value="default" 
									id="sl" 
									checked
								> 
								<label for="sl">No Label</label>
								<br><br>
								<input name="action" type="hidden" value="apply_presets">
								<?php 
								if (isset($_REQUEST['date_array'])) { 
									foreach ($_REQUEST['date_array'] as $d) { ?>
										<input type="hidden" name="date_array[]" value="<?php echo $d?>">
							  <?php }
								}
								if (isset($_REQUEST['movie_id_array'])) { 
									foreach ($_REQUEST['movie_id_array'] as $m) { ?>
										<input type="hidden" name="movie_id_array[]" value="<?php echo $m?>">
							  <?php }
								} ?>
								<input name="submit" type="submit" class="btn btn-success submit" value="Save Changes">
				  <?php } else { ?>
							<em>No sessions found matching your selection.</em>
					</form>				
				  <?php } ?>
	  <?php } 
	    } else { ?>
			<main role="main" class="col-md-9 ml-sm-auto col-lg-10 pt-3 px-4">
				<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
					<h1 class="h2">Session Labels</h1>
				</div>
				<p><?php check_notice("Either you are not logged in or you do not have permission to use this feature.") ?></p>
				<p>Labeling is a quick and easy way of identifying special session times, you can even set up special prices for sessions with particular labels. This is useful for sessions which have &quot;Adults at Children's Prices&quot; or &quot;Blockbuster Pricing&quot;, etc. On this page you can new labels, set ticket prices to labeled sessions, apply labels to sessions (or groups of sessions), view any existing custom prices and restore default labels.</p>
  <?php } ?>
<?php include("inc/footer.inc.php") ?> 
</body>
</html>
