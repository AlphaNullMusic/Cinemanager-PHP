<? 

require("includes/local.inc.php");

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<? include('includes/meta_tags.inc.php') ?>
<script src="includes/generic.js" type="text/javascript"></script>
<title><?=$title_prefix?>Subscriber List</title>
<link href="includes/movie_manager_styles.css" rel="stylesheet" type="text/css">
<script language="javascript" src="includes/generic.js"></script>
</head>
<body bgcolor="#FFFFFF" text="#666666" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<? include("includes/header.inc.php") ?>
<table width="770" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="40"><img src="images/spacer.gif" width="40" height="2"></td>
    <td width="190px" valign="top" bgcolor="#EEEEEE"><? include("includes/nav.inc.php") ?></td>
    <td valign="top">
		<table width="100%"  border="0" cellspacing="0" cellpadding="20">
      <tr>
        <td>
				<? if (check_cinema() && has_permission('newsletters')) { ?>
					<h1>Browse Subscribers</h1>
					Show customers who subscribed:<br />
					<a href="?show=yesterday">Yesterday</a> &nbsp; &nbsp;
					<a href="?show=today">Today</a> &nbsp; &nbsp;
					<a href="?show=week">In the Last Week</a> &nbsp; &nbsp;
					<a href="?show=month">In the Last Month</a> &nbsp; &nbsp;
					<a href="?show=year">In the Last Year</a> &nbsp; &nbsp;
					<a href="?show=all">All</a>
          <br />
					<br />
					<?
					if (isset($_GET['show'])) {
						switch ($_GET['show']) {
							case 'yesterday':
								$from = 'DATE(DATE_SUB(NOW(), INTERVAL 1 DAY))';
								$to = 'DATE(DATE_SUB(NOW(), INTERVAL 1 DAY))';
								break;
							case 'week':
								$from = 'DATE_SUB(NOW(), INTERVAL 1 WEEK)';
								$to = 'NOW()';
								break;
							case 'month':
								$from = 'DATE_SUB(NOW(), INTERVAL 1 MONTH)';
								$to = 'NOW()';
								break;
							case 'year':
								$from = 'DATE_SUB(NOW(), INTERVAL 1 YEAR)';
								$to = 'NOW()';
								break;
							case 'all':
								$from = '0000-00-00';
								$to = 'NOW()';
								break;
							case 'today':
								$from = 'DATE(NOW())';
								$to = 'NOW()';
								break;
							default:
								$from = 'NOW()';
								$to = 'NOW()';
								break;
						}
						$sql="
							SELECT u.first_name, u.last_name, u.email, un.alternate_list1 
							FROM users u
							INNER JOIN user_newsletters un 
								ON u.user_id = un.user_id 
							WHERE un.cinema_id = {$_SESSION['cinema_data']['cinema_id']}
								AND un.last_updated >= $from
								AND un.last_updated <= $to
						";
						if (has_permission('signup_confirmation')) { $sql.="AND u.status='ok' "; }
							$sql.="ORDER BY u.email";
							$res=query($sql);
							$num=mysql_num_rows($res);
							if ($num) {
								echo $num." subscribers added <strong>";
								switch ($_GET['show']) {
									case 'week':
										echo 'in the last week';
										break;
									case 'month':
										echo 'in the last month';
										break;
									case 'year':
										echo 'in the last year';
										break;
									case 'all':
										echo 'in total';
										break;
									default:
										echo strtolower($_GET['show']);
										break;
								}
								echo "</strong><br><br>";
							}
							?>
		          <hr size="1" noshade color="#CCCCCC">
						  <br>
						  <?
							$n=0;
							if (isset($_GET['format']) && $_GET['format'] == 'csv') {
								$output = "Name, Email\n";
							}
							while ($data=mysql_fetch_assoc($res)) {
								if (isset($_GET['format']) && $_GET['format'] == 'csv') {
									$output .= trim($data['first_name'].' '.$data['last_name']).',';
									$output .= $data['email']."\n";
								} else {
									if ($n>0) {
										echo "; ";
									}
									if ($data['alternate_list1']==1) {
										echo "<b>".$data['email']."</b>";
									} else {
										echo $data['email'];
									}
									$n++;
								}
							}
							if (isset($_GET['format']) && $_GET['format'] == 'csv') {
								echo '<textarea style="width:100%;height:30em;">'.trim($output).'</textarea>';
							}
							?>
							<br><br>
		          <hr size="1" noshade color="#CCCCCC">
		          <br>
							<?
							if (isset($_GET['format']) && $_GET['format'] == 'csv') {
								?><a href="<?=str_replace('&format=csv', '', $_SERVER["REQUEST_URI"])?>">Show as a list</a><?
							} else {
								?><a href="<?=$_SERVER["REQUEST_URI"]?><?=(!$_SERVER["QUERY_STRING"])?'?show=all':''?>&format=csv">Show in csv format</a><?
							}
						}
					}
					?></td>
      </tr>
    </table>
    </td>
  </tr>
  <tr>
    <td colspan="3" bgcolor="#FFFFFF"><img src="images/spacer.gif" width="1" height="2"></td>
  </tr>
  <tr>
    <td colspan="3" bgcolor="#DDDDDD"><img src="images/spacer.gif" width="1" height="1"></td>
  </tr>
</table>
<? include("includes/footer.inc.php") ?>
</body>
</html>
