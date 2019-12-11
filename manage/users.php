<?php 
require("inc/manage.inc.php");
if (check_cinema() && has_permission('user_list')) {
	
	// Get user info
	if (isset($_REQUEST['edit']) && $_REQUEST['edit']!='new') {
		$sql = "
			SELECT u.*
			FROM users u
			WHERE u.user_id='{$_REQUEST['edit']}' 
			AND status = 'ok'
		";
		$user_res = $mysqli->query($sql) or user_error("Gnarly: $sql");
		$user_data = $user_res->fetch_assoc();
	}
	
	// Save email change
	if (isset($_REQUEST['edit']) && isset($_POST['save']) && (isset($_POST['email']) || isset($_POST['multi']))) {
		$plain_text = ($_POST['plain_text']==1) ? 1 : 0 ;
		// Add new user
		if ($_REQUEST['edit']=='new') {
			if (isset($_REQUEST['multi']) && !empty($_REQUEST['multi'])) {
				$n = 0;
				$lines = explode("\n", trim($_REQUEST['data']));
				foreach ($lines as $l) {
					preg_match_all('/^([^,]+),?([^,]*)$/i', trim($_REQUEST['data']), $m, PREG_SET_ORDER);
					$parts = explode(",", $l);
					if (count($parts) == 2) {
						$name = trim($parts[0]);
						$email = trim($parts[1]);
					} else {
						$name = 'Member';
						$email = trim($parts[0]);
					}
					if (is_email($email)) {
						$last_name = trim(strstr($name, ' '));
						$first_name = trim(str_replace($last_name, '', $name));
						$user_check_res=$mysqli->query("SELECT user_id FROM users WHERE email='".$mysqli->real_escape_string($email)."'");
						if ($user_check_res->num_rows==0) {
							$sql="
								INSERT INTO users 
								SET email = '".$mysqli->real_escape_string($email)."', 
									first_name = '".$mysqli->real_escape_string($first_name)."',
									last_name = '".$mysqli->real_escape_string($last_name)."',
									date_joined = CURDATE(), 
									status = 'ok',
									plain_text = '$plain_text'
							";
							query($sql);
						} else {
							$user_check = $user_check_res->fetch_assoc();
							$user_id = $user_check['user_id'];
							query("UPDATE users SET status='ok', plain_text='$plain_text' WHERE user_id='$user_id'");
						}
						$n++;
					}
				}								
				header("Location: users.php?conf=$n+subscribers+were+imported.");
				exit;
				
			} else {
				// If user does not exist, create account and set subscription
				$user_check_res = $mysqli->query("SELECT user_id FROM users WHERE email='".$mysqli->real_escape_string($_POST['email'])."'");
				if ($user_check_res->num_rows==0) {
					if (is_email($_POST['email'])) {
						$sql="INSERT INTO users 
							SET email='".$mysqli->real_escape_string($_POST['email'])."',  
							date_joined=CURDATE(), 
							plain_text = '$plain_text',
							status='ok'";
						$mysqli->query($sql);
						$user_id=$mysqli->insert_id;
					} else {
						header("Location: users.php?er=Invalid+email, please try again.");
						exit;
					}
				} else {
					// Set status=ok in case user has signed up before but not confirmed and is now complaining!
					$user_check = $user_check_res->fetch_assoc();
					$user_id = $user_check['user_id'];
					$mysqli->query("UPDATE users SET status='ok' WHERE user_id='$user_id'");
				}
				// Optional updates
				if (has_permission('newsletter_merge')) {
					$sql = "UPDATE users SET status = 'ok'";
					if (isset($_POST['first_name']) && $_POST['first_name'] != '') {
						$sql .= ", first_name='".$mysqli->real_escape_string($_POST['first_name'])."'";
					}
					if (isset($_POST['last_name']) && $_POST['last_name'] != '') {
						$sql .= ", last_name='".$mysqli->real_escape_string($_POST['last_name'])."'";
					}
					$sql .= " WHERE user_id='$user_id'";
					$mysqli->query($sql);
				}
				header("Location: users.php?conf=The+new+subscriber+was+added+successfully.");
				exit;
		}
				
		// Update existing user
		} else {
			$mysqli->query("
				UPDATE users 
				SET 
					email = '".$mysqli->real_escape_string($_POST['email'])."', 
					status = 'ok', 
					plain_text = '$plain_text' 
				WHERE user_id = '".$mysqli->real_escape_string($user_data['user_id'])."'
			") or $mysqli->error;
			$mysqli->query("UPDATE user_newsletters SET plain_text='$plain_text' WHERE user_id='{$user_data['user_id']}' AND cinema_id='{$_SESSION['cinema_data']['cinema_id']}'") or $mysqli->error;
			//optional updates
			if (has_permission('newsletter_merge')) {
				$mysqli->query("UPDATE users SET first_name='".$mysqli->real_escape_string($_POST['first_name'])."', last_name='".$mysqli->real_escape_string($_POST['last_name'])."' WHERE user_id='{$user_data['user_id']}'");
			}
		}
		header("Location: users.php");
		exit;
		
	}
	
	// Delete email from list
	if (isset($_GET['delete']) && isset($_REQUEST['edit'])) {
		$sql="UPDATE users SET status='deleted' WHERE user_id='".$mysqli->real_escape_string($user_data['user_id'])."'";
		$mysqli->query($sql) or user_error("Gnarly: $sql");
		header("Location: users.php");
	}

}

?>
<!DOCTYPE HTML>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Email Subscriber Manager</title>
		<link href="inc/css/bootstrap.min.css" rel="stylesheet" type="text/css">
		<link href="inc/css/dashboard.css" rel="stylesheet">
	</head>
	<body>
		<?php include("inc/header.inc.php"); ?>
		<div class="container-fluid">
			<div class="row">
				<?php include("inc/nav.inc.php"); 
				if (check_cinema() && has_permission('user_list')) {
					if (isset($_REQUEST['edit'])) { ?>
						<main role="main" class="col-md-9 ml-sm-auto col-lg-12 pt-3 px-4">
						<?php echo check_msg(); ?>
							<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
								<h1 class="h2"><?php echo ($_REQUEST['edit']=='new')?"Add A New":"Edit"?> Subscriber</h1>
								<div class="btn-toolbar mb-2 mb-md-0">
									<div class="btn-group mr-2">
										<?php button_4("< Back To User List","users.php","back","right"); ?>
									</div>
								</div>
							</div>
							<form action="users.php" method="post">
						  <?php if (isset($_REQUEST['multi']) && !empty($_REQUEST['multi'])) { ?>
									<strong>Instructions:</strong>
									<ul>
										<li>To enter multiple subscribers at once simply paste them into the box below.</li>
										<li>Put a new line between each customer.</li>
										<li>If you want to include the customer's name, put it before the email with a comma after it.</li>
									</ul>
									<strong>Example format:</strong>
<pre style="margin-left:2em;">
Joe Bloggs, joe@bloggs.co.nz
John Doe, john@doe.co.nz
jane@doe.co.nz
</pre>
									<div class="form-group">
										<label for="data"><strong>Enter your addresses:</strong></label>
										<textarea name="data" class="form-control" cols="80" rows="15"></textarea>
										<input name="multi" type="hidden" value="<?php echo $_REQUEST['multi']?>">	
									</div>
						  <?php } else { ?>
									<div class="form-group">
										<label for="email"><strong>Email Address</strong></label>
										<input name="email" class="form-control" type="text" value="<?php echo isset($user_data['email'])?$user_data['email']:''?>" size="25" maxlength="100">
									</div>
									<?php if (has_permission('newsletter_merge')) { ?>
										<div class="form-group">
											<label for="first_name"><strong>First Name</strong></label>
											<input 
												name="first_name" 
												class="form-control"
												type="text" 
												value="<?php echo isset($user_data['first_name'])?$user_data['first_name']:''?>" 
												size="15" 
												maxlength="100"
											> 
										</div>
										<div class="form-group">
											<label for="last_name"><strong>Last Name</strong></label>
											<input 
												name="last_name" 
												class="form-control"
												type="text" 
												value="<?php echo isset($user_data['last_name'])?$user_data['last_name']:''?>" 
												size="15" 
												maxlength="100"
											> 
										</div>
									<?php } ?>
									<p>
										<strong>Email Format</strong>
										<br>
										<input name="plain_text" type="radio" value="0" id="plain_text_0" <?php echo (!isset($user_data['plain_text']) || (isset($user_data['plain_text']) && $user_data['plain_text']!=1))?"checked":""?>> 
										<label for="plain_text_0">HTML</label><br>
										<input name="plain_text" type="radio" value="1" id="plain_text_1" <?php echo (isset($user_data['plain_text']) && $user_data['plain_text']==1)?"checked":""?>>
										<label for="plain_text_1">Plain	Text</label>
									</p>
							  <?php if (!empty($user_data)) { ?>
										<p>
											<strong>Other Data</strong>
									  <?php $fieldsToIgnore = array('user_id', 'email', 'date_joined', 'last_updated', 'status', 'cinema_id', 'plain_text',);
											foreach ($user_data as $field => $u) {
												if (!in_array($field, $fieldsToIgnore) && !empty($u) && $u != '0000-00-00') {
													echo "<br>" . ucwords(str_replace('_', ' ', $field)) . ": " . str_replace('<br />', ', ', nl2br($u));
												}
											} ?>
										</p>
							  <?php } 
								} ?>
								<p>
									<input name="edit" type="hidden" value="<?php echo $_REQUEST['edit']?>">
									<input name="save" class="btn btn-sm btn-success" type="submit" class="submit" value="Save Changes">
								</p>
							</form>
							<p>
								<?php button_3("Remove From List","?delete=user&edit=".$_REQUEST['edit'],"y","Are you sure you want to remove this user from your list?"); ?>
							</p>
			  <?php } else { ?>
						<main role="main" class="col-md-9 ml-sm-auto col-lg-12 pt-3 px-4">
							<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
					            <h1 class="h2">Subscribers</h1>
					        </div>
							<?php echo check_msg(); ?>
							<?php 
								$sql="
									SELECT u.user_id, u.first_name, u.last_name, u.email
									FROM users u
								";
								if (isset($_GET['search']) && $_GET['search'] != '') { 
									$sql.="WHERE u.email LIKE '%".$mysqli->real_escape_string($_GET['search'])."%' AND status = 'ok' "; 
								} else {
									$sql .= "WHERE status = 'ok' ";
								}
								$sql .= "ORDER BY u.email";
								$user_res=$mysqli->query($sql) or user_error("Gnarly: $sql");
								$num_users = $user_res->num_rows;
							?>
							<p>
								<?php echo $num_users; ?> users found <?php if (isset($_GET['search'])&&$_GET['search']!='') { echo "matching \"".$_GET['search']."\""; } else { echo "in total"; } ?>
							</p>

					  <?php if (empty($_GET['search'])) {
							$weeks = 12; // How many weeks of stats to show
							$startOfWeek = 4; // eg Thursday = 4

							date_default_timezone_set($_SESSION['cinema_data']['timezone']);
							$weeklyStats = array();
							$dayOfWeek = date('N');
							$finalDay = date('Y-m-d', ($dayOfWeek == $startOfWeek) ? time() : strtotime("next Thursday"));
							$finalDayParts = explode('-', $finalDay);
							
							for ($n=1; $n<=$weeks; $n++) {
								$from = mktime(0, 0, 0, $finalDayParts[1], $finalDayParts[2]-$n*7, $finalDayParts[0]);
								$to = mktime(23, 59, 59, $finalDayParts[1], $finalDayParts[2]-$n*7+6, $finalDayParts[0]);
								$sql="
									SELECT COUNT(DISTINCT u.user_id) AS subscribers
									FROM users u
									WHERE u.last_updated >= FROM_UNIXTIME('$from')
										AND u.last_updated <= FROM_UNIXTIME('$to')
										AND u.status = 'ok'
								";
								$subscriberRes = $mysqli->query($sql) or user_error("Gnarly: $sql");
								$subscriberData = $subscriberRes->fetch_assoc();
								$sql="
									SELECT COUNT(DISTINCT nul.user_id) AS unSubscribers
									FROM newsletter_user_log nul
									INNER JOIN users u
										ON u.user_id = nul.user_id
									WHERE nul.timestamp >= FROM_UNIXTIME('$from')
										AND nul.timestamp <= FROM_UNIXTIME('$to')
										AND nul.action IN ('unsubscribe', 'bounce')
								";
								$unSubscriberRes = $mysqli->query($sql) or user_error("Gnarly: $sql");
								$unSubscriberData = $unSubscriberRes->fetch_assoc();
								if (!isset($total)) {
									$total = $num_users;
								} else {
									$total = $total -$subscriberData['subscribers'] + $unSubscriberData['unSubscribers'];
								}
								$weeklyStats[] = array(
									'from' => $from,
									'to' => $to,
									'fromDateShort' => date('j M', $from),
									'fromDate' => date('Y-m-d', $from),
									'toDate' => date('Y-m-d', $to),
									'subscribers' => $subscriberData['subscribers'],
									'unSubscribers' => $unSubscriberData['unSubscribers'],
									'total' => $total
								);
							}
							$weeklyStats = array_reverse($weeklyStats); 
							?>
							<div class="d-none d-md-block">
							<h2>Quarterly Trend</h2>
							<div id="chart_div"></div>
								<script type="text/javascript" src="https://www.google.com/jsapi"></script>
								<script type="text/javascript">
									google.load("visualization", "1", {packages:["corechart"]});
									google.setOnLoadCallback(drawChart);
									function drawChart() {
										var data = google.visualization.arrayToDataTable([
											['Date', 'Subscribers']
									  <?php foreach ($weeklyStats as $s) {
												echo ",['{$s['fromDateShort']}', {$s['total']} ]";
											} ?>
										]);
										var options = {
											isStacked: false,
											curveType: 'function',
											focusTarget: 'category',
											colors: ['#719F2A', '#006A72'],
											fontSize: 12,
											hAxis: {titleTextStyle: {color: '#666666'}},
											vAxis: {titleTextStyle: {color: '#666666'}},
											width: 680,
											height: 200,
											chartArea: {left:"6%", top:"4%", width:"82%", height:"84%"},
											legend: {'position': 'none'}
										};
										var chart = new google.visualization.AreaChart(document.getElementById('chart_div'));
										chart.draw(data, options);
									}
								</script>
							</div>
					  <?php } ?>
							<hr>
							<p>
								<h2>Find Subscribers By Email</h2>
								<p>Search for a subscriber to remove or edit them.</p>
								<form name="movie_search" method="get" action="users.php">
									<input name="search" type="text" id="search" value="<?=(isset($_GET['search']))?$_GET['search']:''?>" size="20" maxlength="100" style="margin-bottom:10px;">
									<input name="Submit" class="btn btn-sm btn-primary" type="submit" class="submit" value="Search Subscribers">
								</form>
							</p>
					  <?php if (isset($_GET['search']) && $num_users > 0) { ?>
								<table class="table table-striped">
							  <?php while ($user_data=$user_res->fetch_assoc()) { ?>
										<tr>
											<td>
												<a href="?edit=<?php echo $user_data['user_id'];?>"><?php echo $user_data['email']?></a>
											</td>
										</tr>
							  <?php } ?>
								</table>
					  <?php } ?>
							<br><hr><br>
							<p><?php button_1("Add One Subscriber","?edit=new") ?></p>
							<p><?php button_1("Add Many Subscribers","?edit=new&multi=true") ?></p>
							<p><?php button_1("Download All Subscribers","user_download.php") ?></p>
							<p>Download new subscribers from <a href="user_download.php?show=today">today</a>, <a href="user_download.php?show=yesterday">yesterday</a>, the last <a href="user_download.php?show=week">week</a>, <a href="user_download.php?show=month">month</a> or <a href="user_download.php?show=year">year</a>.</p>
			  <?php }
				} else { ?>
					<main role="main" class="col-md-9 ml-sm-auto col-lg-10 pt-3 px-4">
						<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
							<h1 class="h2">Manage Email Newsletter Subscriptions</h1>
						</div>
						<p><?php check_notice("Either you are not logged in or you do not have permission to use this feature.") ?></p>
						<p>This page is used to manage email newsletter subscriptions. Although people can join cinema email list themselves, directly through the cinema's own website, members can also be manually added or removed here at any time.</p>
	  <?php } ?>
  <?php include("inc/footer.inc.php") ?>
	</body>
</html>
