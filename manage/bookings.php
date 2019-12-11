<?php 
require("inc/manage.inc.php");
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
						<main role="main" class="col-md-9 ml-sm-auto col-lg-12 pt-3 px-4">
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
										DATE_FORMAT(s.time,'%e %b %Y') AS session_date,
										LOWER(DATE_FORMAT(bl.booked_on,'%l:%i%p')) AS booked_time,
										DATE_FORMAT(bl.booked_on, '%e %b %Y') AS booked_date,
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
							<div class="table-responsive">
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
                                                                                        <td><?php echo $booking_data['booked_time'].", ".$booking_data['booked_date'];?></td>
										</tr>
							  <?php } ?>
								</table>
							</div>
					<?php } ?>
			  <?php } else { ?>
					<main role="main" class="col-md-9 ml-sm-auto col-lg-12 pt-3 px-4">
						<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
							<h1 class="h2">View Booking Requests</h1>
						</div>
						<p><?php check_notice("Either you are not logged in or you do not have permission to use this feature.") ?></p>
						<p>This page is used to view bookings made through the cinema website.</p>
	  <?php } ?>
  <?php include("inc/footer.inc.php") ?>
	</body>
</html>
