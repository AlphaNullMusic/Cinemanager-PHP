<?php 
require("inc/manage.inc.php");
if (check_cinema() && has_permission('log_bookings')) {
	
	// Get user info
	/*if (isset($_REQUEST['edit']) && $_REQUEST['edit']!='new') {
		$sql = "
			SELECT u.*
			FROM users u
			WHERE u.user_id='{$_REQUEST['edit']}' 
		";
		$user_res = $mysqli->query($sql) or user_error("Gnarly: $sql");
		$user_data = $user_res->fetch_assoc();
	}*/
	
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

}

?>
<!DOCTYPE HTML>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>View Booking Requests</title>
		<link href="inc/css/bootstrap.min.css" rel="stylesheet" type="text/css">
		<link href="inc/css/dashboard.css" rel="stylesheet">
	</head>
	<body>
		<?php include("inc/header.inc.php"); ?>
		<div class="container-fluid">
			<div class="row">
				<?php include("inc/nav.inc.php"); 
				if (check_cinema() && has_permission('log_bookings')) { ?>
						<main role="main" class="col-md-9 ml-sm-auto col-lg-10 pt-3 px-4">
							<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
					            <h1 class="h2">Booking Request List</h1>
					        </div>
							<?php echo check_msg(); ?>
							<?php 
								date_default_timezone_set($_SESSION['cinema_data']['timezone']);
								$sql="
									SELECT bl.*, 
										s.movie_id, 
										s.time, 
										LOWER(DATE_FORMAT(s.time,'%l:%i%p')) AS session_time,
										DATE_FORMAT(s.time,'%e %b') AS session_date,
										m.title
									FROM booking_log bl
									INNER JOIN sessions s
										ON bl.session_id = s.session_id
									INNER JOIN movies m
										ON s.movie_id = m.movie_id
								";
								if (isset($_GET['search']) && $_GET['search'] != '') {
									$sql.="
										WHERE (bl.email like '%".$mysqli->real_escape_string($_GET['search'])."%')
										OR (bl.name like '%".$mysqli->real_escape_string($_GET['search'])."%')
										OR (bl.phone like '%".$mysqli->real_escape_string($_GET['search'])."%')
									";
								}
								$sql.=" ORDER BY bl.booked_on DESC";
								$booking_res=$mysqli->query($sql) or user_error("Error at: $sql");
								$num_bookings = $booking_res->num_rows;
							?>
							<p>
								<?php echo $num_bookings; ?> requests found <?php if (isset($_GET['search'])&&$_GET['search']!='') { echo "matching \"".$_GET['search']."\""; } else { echo "in total"; } ?>
							</p>
							<p>
								<h2>Find Request By Details</h2>
								<p>Search for a specific request by either name, email or phone number.</p>
								<form name="booking_search" method="get" action="bookings.php">
									<input name="search" type="text" id="search" value="<?=(isset($_GET['search']))?$_GET['search']:''?>" size="20" maxlength="100">
									<input name="Submit" class="btn btn-sm btn-primary" type="submit" class="submit" value="Search">
								</form>
							</p>
							<hr>
					  <?php if ($booking_res->num_rows != 0) { ?>
								<table class="table">
									<thead class="thead-light">
										<th scope="col">#</th>
                                                                                <th scope="col">Name</th>
                                                                                <th scope="col">Email</th>
                                                                                <th scope="col">Phone</th>
                                                                                <th scope="col">Details</th>
                                                                                <th scope="col">Date</th>
									</thead>
							  <?php while ($booking_data=$booking_res->fetch_assoc()) { ?>
										<tr>
											<th scope="row"><?php echo $booking_data['id'];?></th>
											<td><?php echo $booking_data['name'];?></td>
											<td><?php echo $booking_data['email'];?></td>
                                                                                        <td><?php echo $booking_data['phone'];?></td>
                                                                                        <td>
												<button 
													type="button" 
													class="btn btn-secondary btn-sm" 
													data-toggle="tooltip" 
													data-html="true" 
													data-placement="left" 
													title="
													<?php
														$title = "<b><em>{$booking_data['title']}<br>{$booking_data['session_date']} - {$booking_data['session_time']}</em></b><br><br>";
														if ($booking_data['adults'] != 0) {
															$title .= "<b>Adults:</b> {$booking_data['adults']}<br>";
														}
														if ($booking_data['children'] != 0) {
                                                                                                                        $title .= "<b>Adults:</b> {$booking_data['children']}<br>";
                                                                                                                }
														if ($booking_data['seniors'] != 0) {
                                                                                                                        $title .= "<b>Seniors:</b> {$booking_data['seniors']}<br>";
                                                                                                                }
														if ($booking_data['students'] != 0) {
                                                                                                                        $title .= "<b>Students:</b> {$booking_data['students']}<br>";
                                                                                                                }
														if ($booking_data['wheelchair'] == 1) {
															$title .= '<em>Requested Wheelchair</em> ';
														}
														echo $title;
													?>
													"
												>
													Show
												</button>
											</td>
                                                                                        <td><?php echo $booking_data['booked_on'];?></td>
										</tr>
							  <?php } ?>
								</table>
					<?php } ?>
			  <?php } else { ?>
					<main role="main" class="col-md-9 ml-sm-auto col-lg-10 pt-3 px-4">
						<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
							<h1 class="h2">View Booking Requests</h1>
						</div>
						<p><?php check_notice("Either you are not logged in or you do not have permission to use this feature.") ?></p>
						<p>This page is used to view bookings made through the cinema website.</p>
	  <?php } ?>
  <?php include("inc/footer.inc.php") ?>
	</body>
</html>
