<?php
$use_pdo = true;
require("inc/manage.inc.php");

if (check_cinema() && has_permission('newsletters')) {

	$db = new db;
	// delete a message
	if (isset($_GET['action']) && $_GET['action']=='delete' && isset($_GET['newsletter_id']) && is_numeric($_GET['newsletter_id'])) {
		$sql = "
			DELETE FROM newsletters
			WHERE newsletter_id = :newsletter_id
				AND status = 'new'
		";
		$stmt = $db->prepare($sql);
		$stmt->execute(array(
			':newsletter_id' => $_GET['newsletter_id']
		));
		$stmt = NULL;
		header("Location: newsletter_overview.php?conf=Newsletter deleted successfully");
		die;
	}
	
	// get some stats, grouped by week
	// recipients reached, recipients clicked, recipients recipients bounced, recipients unsubscribed
	
	date_default_timezone_set($_SESSION['cinema_data']['timezone']);
	
	$weeks = 12;				// how many weeks of stats to show
	$startOfWeek = date('N'); 	// eg Thursday = 4
	
	// prepared statements
	/*
	$sql = "
		SELECT COUNT(DISTINCT nl.user_id) AS total
		FROM newsletter_log nl
		INNER JOIN newsletters n
			ON n.newsletter_id = nl.newsletter_id
			AND n.cinema_id = '{$_SESSION['cinema_data']['cinema_id']}'
			AND n.status = 'sent'
		WHERE nl.timestamp >= FROM_UNIXTIME(:from)
			AND nl.timestamp <= FROM_UNIXTIME(:to)
			AND nl.status = 'sent'
	";
	$stmtWeeklyTotal = $db->prepare($sql);
	$stmtWeeklyTotal->bindParam(':from', $from);
	$stmtWeeklyTotal->bindParam(':to', $to);
	*/
	
	$sql = "
		SELECT COUNT(DISTINCT nul.user_id) AS opened
		FROM newsletter_user_log nul
		INNER JOIN newsletters n
			ON n.newsletter_id = nul.newsletter_id
			AND n.status = 'sent'
		WHERE nul.time >= FROM_UNIXTIME(:from)
			AND nul.time <= FROM_UNIXTIME(:to)
			AND nul.action = 'open'
	";
	$stmtWeeklyOpened = $db->prepare($sql);
	$stmtWeeklyOpened->bindParam(':from', $from);
	$stmtWeeklyOpened->bindParam(':to', $to);
	
	$sql = "
		SELECT COUNT(DISTINCT nul.user_id) AS clicked
		FROM newsletter_user_log nul
		INNER JOIN newsletters n
			ON n.newsletter_id = nul.newsletter_id
			AND n.status = 'sent'
		WHERE nul.time >= FROM_UNIXTIME(:from)
			AND nul.time <= FROM_UNIXTIME(:to)
			AND nul.action = 'click'
	";
	$stmtWeeklyClicked = $db->prepare($sql);
	$stmtWeeklyClicked->bindParam(':from', $from);
	$stmtWeeklyClicked->bindParam(':to', $to);
	
	$weeklyStats = array();
	$dayOfWeek = date('N');
	$finalDay = date('Y-m-d', ($dayOfWeek == $startOfWeek) ? time() : strtotime("next Thursday"));
	$finalDayParts = explode('-', $finalDay);
	for ($n=1; $n<=12; $n++) {
		$from = mktime(0, 0, 0, $finalDayParts[1], $finalDayParts[2]-$n*7, $finalDayParts[0]);
		$to = mktime(23, 59, 59, $finalDayParts[1], $finalDayParts[2]-$n*7+6, $finalDayParts[0]);
		//$stmtWeeklyTotal->execute();
		//$total = $stmtWeeklyTotal->fetch(PDO::FETCH_ASSOC);
		$stmtWeeklyOpened->execute();
		$opened = $stmtWeeklyOpened->fetch(PDO::FETCH_ASSOC);
		$stmtWeeklyClicked->execute();
		$clicked = $stmtWeeklyClicked->fetch(PDO::FETCH_ASSOC);
		$weeklyStats[] = array(
			'from' => $from,
			'to' => $to,
			'fromDateShort' => date('j M', $from),
			'fromDate' => date('Y-m-d', $from),
			'toDate' => date('Y-m-d', $to),
			//'total' => $total['total'],
			'opened' => $opened['opened'],
			'clicked' => $clicked['clicked']
		);
	}
	$weeklyStats = array_reverse($weeklyStats);

}

?>
<!DOCTYPE HTML>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<script src="includes/generic.js" type="text/javascript"></script>
		<title><?php echo $title_prefix?> <?php echo (isset($_SESSION['cinema_data']))?"Movie Lists &amp; Sessions":"Website Content Management For Cinemas";?></title>
		<link href="inc/css/bootstrap.min.css" rel="stylesheet" type="text/css">
		<!--<link href="includes/css/styles.css" rel="stylesheet" type="text/css">-->
		<link href="inc/css/dashboard.css" rel="stylesheet">
	</head>
	<body>
		<?php include("inc/header.inc.php");?>
		<div class="container-fluid">
		<div class="row">
			<?php include("inc/nav.inc.php");?>
			<?php if (check_cinema() && has_permission('newsletters')) { ?>
				<main role="main" class="col-md-9 ml-sm-auto col-lg-10 pt-3 px-4">
					<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
						<h1 class="h2">Email Newsletters</h1>
						<div class="btn-toolbar mb-2 mb-md-0">
							<div class="btn-group mr-2">
								<?php button_1("Create a Newsletter","newsletters.php","plus","right") ?>
							</div>
						</div>
					</div>
					<?php
					if (isset($_GET['conf'])) { confirm($_GET['conf']); } 
					if (isset($_GET['er'])) { confirm($_GET['er'],'er'); } ?>
					<h3>Quarterly Trend</h3>
					<div id="chart_div"></div>
						<script type="text/javascript" src="https://www.google.com/jsapi"></script>
						<script type="text/javascript">
							google.load("visualization", "1", {packages:["corechart"]});
							google.setOnLoadCallback(drawChart);
							function drawChart() {
								var data = google.visualization.arrayToDataTable([
									['Week', 'Opens', 'Clicks']
								  <?php foreach ($weeklyStats as $s) {
											//if ($s['total'] > 0) {
												echo ",['{$s['fromDateShort']}', {$s['opened']}, {$s['clicked']} ]";
											//}
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
									chartArea: {left:"8%", top:"4%", width:"88%", height:"84%"},
									legend: {'position': 'none'}
								};
								var chart = new google.visualization.AreaChart(document.getElementById('chart_div'));
								chart.draw(data, options);
							}
						</script>
					<?php 
					// get a list of all the newsletters, grouped by status
					$db = new db;
					$newsletters = array(
						'pending' => array ('name' => 'Scheduled'), 
						'new' => array ('name' => 'Draft'),
						'sent' => array ('name' => 'Sent'),
					);
					foreach ($newsletters as $key => $value) {
						$order = ($key == 'new') ? 'n.last_edited DESC' : 'n.send_after DESC';
						$db->query("SET time_zone = '+12:00'");
						$sql = "
							SELECT n.newsletter_id, n.subject, n.send_after, DATE_FORMAT(n.last_edited, '%e %M %Y') AS editDate, LOWER(DATE_FORMAT(n.last_edited, '%l:%i%p')) AS editTime, DATE_FORMAT(n.send_after, '%e %M %Y') AS sendDate, LOWER(DATE_FORMAT(n.send_after, '%l:%i%p')) AS sendTime, n.status,
								COUNT(nl.user_id) AS totalRecipients
							FROM newsletters n
							LEFT JOIN newsletter_log nl
								ON nl.newsletter_id = n.newsletter_id
							WHERE n.last_edited > '2013-03-01'
								AND n.status = :status
							GROUP BY n.newsletter_id
							ORDER BY $order
						";
						$stmt = $db->prepare($sql);
						$stmt->execute(array(
							':status' => $key
						));
						$newsletters[$key]['data'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
						$stmt = NULL;
					}

					// get the cinema domain for newsletter preview
					if (!empty($newsletters['sent'])) {
						$sql = "
							SELECT url
							FROM settings
							WHERE id = 1
							LIMIT 1
						";
					}
					$stmt = $db->prepare($sql);
					$stmt->execute(array(
						':cinema_id' => $_SESSION['cinema_data']['cinema_id']
					));
					$cinemaUrl = $stmt->fetch(PDO::FETCH_ASSOC);
					$stmt = NULL;
			    
					// prepared statement for fetching stats on sent newsletters
					$sql = "
						SELECT SUM(IF(action='open', 1, 0)) AS opened, SUM(IF(action='click', 1, 0)) AS clicked
						FROM newsletter_user_log
						WHERE newsletter_id = :newsletter_id
						GROUP BY newsletter_id
					";
					$stmtFetchStats = $db->prepare($sql);
					$stmtFetchStats->bindParam(':newsletter_id', $newsletter_id);
					
					$url = urlencode('https://'.$cinemaUrl['url'].'/');
					
					//echo "<pre>";
					//print_r($newsletters);
					//echo "</pre>";
					
					if (!empty($newsletters['new']['data'])) { ?>
						<h2>Draft Newsletters</h2>
						<table class="sortable" width="100%" cellpadding="0" cellspacing="0">
							<tbody>
						  <?php foreach ($newsletters['new']['data'] as $n) { ?>
									<tr>
										<td><a href="newsletters.php?newsletter_id=<?php echo $n['newsletter_id']?>"><?php echo $n['subject']?></a></td>
										<td>Last edited on <?php echo $n['editDate']?> at <?php echo $n['editTime']?></td>
										<td><a href="?action=delete&newsletter_id=<?php echo $n['newsletter_id']?>" onClick="confirmDelete()">Delete</a></td>
									</tr>
						  <?php } ?>
							</tbody>
						</table>
			  <?php } 

					if (!empty($newsletters['pending']['data'])) { ?>
						<h2>Scheduled Newsletters</h2>
						<table class="sortable" width="100%" cellpadding="0" cellspacing="0">
							<tbody>
					      <?php foreach ($newsletters['pending']['data'] as $n) { ?>
									<tr>
										<td><a href="<?php echo $config['cinema_url']?>email_newsletter.php?urloverride=<?php echo $url?>&newsletter_id=<?php echo $n['newsletter_id']?>" onclick="window.open(this.href,'targetWindow','toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=700,height=700'); return false;"><?php echo $n['subject']?></a></td>
										<td><?php echo "Scheduled for {$n['sendDate']} at {$n['sendTime']}"?></td>
										<td><a href="newsletters.php?newsletter_id=<?php echo $n['newsletter_id']?>">Edit or Reschedule</a></td>
										<td><a href="?action=delete&newsletter_id=<?php echo $n['newsletter_id']?>" onClick="confirmDelete()">Delete</a></td>
										<!--<td><a href='?action=cancelSend&newsletter_id={$n['newsletter_id']}'>Cancel</a></td>-->
									</tr>
					      <?php } ?>
							</tbody>
						</table>
			  <?php } 

					if (!empty($newsletters['sent']['data'])) { ?>
						<h2>Sent Newsletters</h2>
						<table class="sortable" width="100%" cellpadding="0" cellspacing="0">
							<tbody>
					      <?php foreach ($newsletters['sent']['data'] as $n) { ?>
									<tr>
										<td><a href="email_newsletter.php?urloverride=<?php echo $url?>&newsletter_id=<?php echo $n['newsletter_id']?>&static=true" onclick="window.open(this.href,'targetWindow','toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=700,height=700'); return false;"><?php echo $n['subject']?></a></td>
										<td><?php echo "Sent on {$n['sendDate']}"?></td>
										<td><?php echo"Recipients: {$n['totalRecipients']}"?></td>
									<?php
										$newsletter_id = $n['newsletter_id'];
										$stmtFetchStats->execute();
										$stats = $stmtFetchStats->fetch(PDO::FETCH_ASSOC);
										if ($stats) {
											echo "<td>Opens: {$stats['opened']}</td>";
											echo "<td>Clicks: {$stats['clicked']}</td>";
										} else {
											echo "<td>&nbsp;</td><td>&nbsp;</td>";
										}
									?>
									</tr>
					      <?php } ?>
							</tbody>
						</table>
			  <?php } 
				} else { ?>
					<main role="main" class="col-md-9 ml-sm-auto col-lg-10 pt-3 px-4">
						<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
							<h1 class="h2">Email Newsletters</h1>
						</div>
						<p><?php echo check_notice("Either you are not logged in or you do not have permission to use this feature.") ?></p>
						<p>This page gives access to our automated email marketing system exclusively developed for New Zealand cinemas. You can send personalised, branded, formatted,  bulk HTML email messages to your website members. Session times and movie details are automatically gathered from your movie list and embedded in your emails, potentially saving your hours of work.</p>
						<p> If you are a cinema operator and would like more information on any of our email marketing services or any other feature mentioned on this website, please don't hesitate to <a href="contact.php">contact us</a>.</p>
		  <?php } ?>
		<?php include("inc/footer.inc.php") ?>
	</body>
</html>