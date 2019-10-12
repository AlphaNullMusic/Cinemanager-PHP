<?php 
require("inc/manage.inc.php");

if (check_cinema() && has_permission('sessions')) {
	
	//date_default_timezone_set($_SESSION['cinema_data']['timezone']);
	date_default_timezone_set('America/Chicago'); // provides the right offset for current server
	$sessions_displayed = 63;
	$weeks_displayed = 10;
	$seconds_in_day = 24*3600;
	
	// Get movie info
	if (isset($_REQUEST['movie_id'])) {
		// Create array of dates to display
		for ($n = 0; $n < $sessions_displayed; $n++) {
			$session_date_timestamp = time()+$seconds_in_day*$n;
			$session_data[$n]['friendly'] = date("D j M", $session_date_timestamp);
			$session_data[$n]['mysql'] = date("Y-n-j", $session_date_timestamp);
			// Get session summary for each day
			$day_start = $session_data[$n]['mysql'].' 00:00:00';
			$day_end = $session_data[$n]['mysql'].' 23:59:59';
			$sql="
				SELECT 
					DATE_FORMAT(s.time,'%l:%i%p') AS session, 
					s.label_id
				FROM sessions s 
				WHERE 
					s.movie_id='{$_REQUEST['movie_id']}' 
					AND s.time>='$day_start' 
					AND s.time<='$day_end' 
				ORDER BY s.time
			";
			$this_session_res = $mysqli->query($sql) or user_error("Gnarly: $sql");
			unset($session_times,$preset_group_id);
			while ($this_session_data = $this_session_res->fetch_assoc()) {
				if (isset($session_times)) {
					$session_times .= ', ';
				} else {
					$session_times = '';
				}
				$session_times.=strtolower($this_session_data['session']);
				if ($this_session_data['comments']) {
					$session_times.=' ('.$this_session_data['comments'].')';
				}
				if (isset($this_session_data['session_preset_group_id']) && $this_session_data['session_preset_group_id']>0) {
					$preset_group_id=$this_session_data['session_preset_group_id'];
				}
			}
			$session_data[$n]['sessions']=(isset($session_times))?$session_times:NULL;
			$session_data[$n]['preset']=(isset($preset_group_id))?$preset_group_id:NULL;
		}
		// Get generic movie data
		$sql = "SELECT title FROM movies WHERE movie_id='{$_REQUEST['movie_id']}'"; 
		$movie_res = $mysqli->query($sql) or user_error("Gnarly: $sql");
		if ($movie_res->num_rows != 0) {
			$movie_data = $movie_res->fetch_assoc();
		} else {
			header("Location: movies.php?er=Cannot+edit+session+times+as+movie+not+found.");
		}
	} else {
		header("Location: movies.php?er=Cannot+edit+session+times+as+movie+not+found.");
	}
	
	
	// Update sessions
	if (isset($_POST['submit']) && isset($_POST['movie_id'])) {
		// For each submitted day, replace existing sessions with new ones (if they have changed)
		for ($n=0; $n<$sessions_displayed; $n++) {
			// Get variables for current day
			$session_name = "session_".$n;
			$session_date_timestamp = time()+$seconds_in_day*$n;
			$session_date = date("Y-m-d",$session_date_timestamp);
			if (isset($_POST[$session_name])) {
				$submitted_session = trim($_POST[$session_name]);
				// If sessions have changed, use manage_sessions class to perform updates
				if ($submitted_session != $session_data[$n]['sessions']) {
					$sp = new manage_sessions();
					$sp->session_date = $session_date;
					$sp->movie_id = $_POST['movie_id'];
					//$sp->cinema_id = $_SESSION['cinema_data']['cinema_id'];
					if ($submitted_session != '') {
						$sp->process_session_string($submitted_session);
					} else {
						$sp->clear_day();
					}
				}
			}
		}
		// Clear smarty cache
		//update_site_stats();
		//update_movie_cache($_POST['movie_id'],true);
		smarty_clear_cache($_SESSION['cinema_data']['cinema_id'],$_POST['movie_id']);
		//redirect
		if (isset($_POST['redir']) && $_POST['redir']=='edit_prices') {
			$location="labels.php?movie_id_array[]=".$_POST['movie_id'];
		} elseif ($_POST['redir']=='edit_movie') {
			$location="movie_edit_details.php?movie_id=".$_POST['movie_id'];
		} else {
			$location="movies.php?changed_id=".$_POST['movie_id'];
		}
		header("Location: $location");
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
    <title><?=$title_prefix?><?=($_SESSION['cinema_data'])?"Movie Lists &amp; Sessions":"Website Content Management For Cinemas";?></title>
    <link href="inc/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="inc/css/dashboard.css" rel="stylesheet">
  </head>
  <body>
  <?php include("inc/header.inc.php");?>
  <div class="container-fluid">
    <div class="row">
      <?php include("inc/nav.inc.php");
		if (check_cinema() && (has_permission('sessions'))) { ?>
            <main role="main" class="col-md-9 ml-sm-auto col-lg-10 pt-3 px-4">
				<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
					<h1 class="h2">Edit Session Times</h1>
					<div class="btn-toolbar mb-2 mb-md-0">
						<div class="btn-group mr-2">
							<?php button_1("< Back To Movie List","movies.php","back","right") ?> 
						</div>
					</div>
				</div>
				<form name="form1" method="post" action="<?php echo $_SERVER['PHP_SELF']?>"> 
					<h3><?php echo $movie_data['title'] ?></h3>
					<ul>
						<li>Sessions may be automatically ordered chronologically and/or re-formatted for consistancy.</li>
						<li>Sessions should be entered in the format &quot;10:30am, 1:00pm, 3:30pm&quot;. Common variations are also acceptable, please check our <a href="session_guide.php" onClick="flvFPW1(this.href,'ps','width=400,height=320,status=yes,scrollbars=yes,resizable=yes',1,2,2);return document.MM_returnValue">session time entry guidelines</a> for more details.</li>
						<li>Sessions marked with a <img src="inc/icons/icon_exclaim_onyellow.gif" width="15" height="15" align="absmiddle"> have custom labels applied.<br>
							If you modify one of these sessions, the labels will be removed.</li>
					</ul>
					<table border="0" cellspacing="0" cellpadding="1"> 
					<?php
						$n = 0;
						$week = 1;
						foreach ($session_data as $data) { 
							if (preg_match('/^Thu/',$data['friendly']) && $n>0) {
								$week++;
								if ($week == 4) { ?>
									<tr id="show_more_button">
										<td colspan="4" align="right">
											<a href="javascript:;" onclick="show_extra_sessions();">
												Show More
											</a>
											&nbsp;
										</td>
									</tr>
							  <?php if (!isset($hidden_row_start)) {
										$hidden_row_start=$n;
									}
								}
								if ($week >= $weeks_displayed) {
									$hidden_row_end=$n;
									break;
								} ?>
								<tr id="session_row_<?php echo $n?>_divider"<?php echo ($week>=4)?' style="display:none;"':''?>>
									<td colspan="4" background="images/divider_horiz_grey2_tall.gif">
										<img src="images/spacer.gif" width="1" height="21">
									</td>
								</tr>
					  <?php } ?>
							<tr id="session_row_<?php echo $n?>"<?php echo ($week>=4)?' style="display:none;"':''?><?php echo ($data['preset'])?' class="custom_session"':''?>>
								<td align="right" nowrap>
									<?php echo $data['friendly']?>
								</td>
								<td nowrap>&nbsp;</td>
								<td>
									<input 
										name="session_<?php echo $n?>" 
										type="text" 
										value="<?php echo $data['sessions']?>" 
										size="45" 
										maxlength="100"
									>
									&nbsp;
								</td>
								<td nowrap>
							  <?php if ($data['preset']) { ?>
										<img 
											src="inc/icons/icon_exclaim_onyellow.gif" 
											width="15" 
											height="15" 
											align="absmiddle" 
											alt="Changing these sessions will reset their pricing."
										> 
										<a href="labels.php?movie_id_array[]=<?php echo $_REQUEST['movie_id']?>">
											labels applied
										</a>
							  <?php } else { 
										echo "&nbsp;"; 
									} ?>
								</td>
							</tr>
					  <?php $n++;
						} ?>
						<tr>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>
								<br>
								<strong>Where to next?</strong><br>
								<input name="redir" type="radio" id="redir_2" value="movie_list" checked> <label for="redir_2">Back to movie list</label><br>
								<input name="redir" type="radio" id="redir_3" value="edit_movie"> <label for="redir_3">Edit movie details</label><br>
								<input name="movie_id" type="hidden" value="<?php echo $_REQUEST['movie_id'] ?>">
								<input name="submit" type="submit" class="btn btn-success submit" value="Save Changes">
							</td>
							<td>&nbsp;</td>
						</tr>
					</table>
				</form>
				<script type="text/javascript" language="javascript">
					function show_extra_sessions() {
						for (i=<?php echo $hidden_row_start?>; i<=<?php echo $hidden_row_end?>; i++) {
						  	var row = document.getElementById('session_row_'+i);
						  	var divider = document.getElementById('session_row_'+i+'_divider');
							if (row != null) {
						  		row.style.display = "table-row";
							}
							if (divider != null) {
						  		divider.style.display = "table-row";
							}
						}
						document.getElementById('show_more_button').style.display = "none";
					}
				</script>
  <?php } else { ?>
			<main role="main" class="col-md-9 ml-sm-auto col-lg-10 pt-3 px-4">
				<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
					<h1 class="h2">Edit Session Times</h1>
				</div>
				<p><?php check_notice("Either you are not logged in or you do not have permission to use this feature.") ?></p>
  <?php } ?>						
<? include("inc/footer.inc.php") ?> 
</body>
</html>
