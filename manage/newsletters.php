<?php 
ini_set('display_errors',1); 
error_reporting(E_ALL);
require("inc/manage.inc.php");
if (check_cinema() && has_permission('newsletters')) {
	// Get cinema info
	$sql = "SELECT * FROM settings WHERE id='1'";
	$cinema_res = $mysqli->query($sql);
	$cinema_data = $cinema_res->fetch_assoc();
	
	if (isset($_POST['goto']) && $_POST['goto']=='send') {
		check_referrer();

		// Generate html
		$message_html=file_get_contents($config['email_url']."?newsletter_id=".$_REQUEST['newsletter_id']);
		$message_html=preg_replace('#\"uploads\/#','"'.$config['cinema_url']."uploads/",$message_html);
		$message_html=preg_replace("#\'uploads\/#","'".$config['cinema_url']."uploads/",$message_html);
		$message_text=strip_tags(file_get_contents($config['email_url']."?newsletter_id=".$_REQUEST['newsletter_id']."&plaintext=y"));

		// Prepare message for sending
		if ($_REQUEST['recipients']!='self') {

			// Rewrite links
			function collectLinks($matches) {
				global $config, $mysqli;
				$prefix = '';
				$suffix = '';
				if (count($matches) == 4) {
					$prefix = $matches[1];
					$suffix = $matches[3];
					$url = $matches[2];
					$format = 'html';
				} else {
					$url = $matches[0];
					$format = 'text';
				}
				if ($url != '<!--unsub-->') {
					$sql = "
						INSERT INTO newsletter_links
						SET newsletter_id = '".$mysqli->real_escape_string($_REQUEST['newsletter_id'])."', 
							url = '".$mysqli->real_escape_string($url)."'
					";
					$mysqli->query($sql);
					$linkId = $mysqli->insert_id;
					$url = "{$config['cinema_url']}e/link.php?l={$linkId}&u=[MMUID]";
				}
				$return = $prefix.$url.$suffix;
				return $return;
			}
			$message_html = preg_replace_callback('/(href=["\'])([^"\']+)(["|\'])/im', 'collectLinks', $message_html);
			$message_text = preg_replace_callback('/https?:\/\/.+/i', 'collectLinks', $message_text);
				
			// Optional vars
			$list="";
			if ($_POST['recipients']=='altlist' || $_POST['recipients']=='nonaltlist' || $_POST['recipients']=='altlist2') {
				$list=$_POST['recipients'];
			}
			$merge = (has_permission('newsletter_merge'))?1:0;

			// Schedule for sending
			$sql = "
				UPDATE newsletters SET 
					message_text='".$mysqli->real_escape_string($message_text)."', 
					message_html='".$mysqli->real_escape_string($message_html)."', 
					mailing_list='$list', 
					merge='$merge', 
					send_after='{$_REQUEST['y']}-{$_REQUEST['m']}-{$_REQUEST['d']} {$_REQUEST['t']}',
					status='pending' 
				WHERE newsletter_id='{$_REQUEST['newsletter_id']}' 
			";
			query($sql) or $mysqli->error();
			header("Location: newsletters.php?complete=scheduled&newsletter_id={$_REQUEST['newsletter_id']}");
			exit;
		} else {
			// Send test now
			$email_res = $mysqli->query("
											SELECT 
												reply_address, 
												subject 
											FROM newsletters 
											WHERE newsletter_id='{$_REQUEST['newsletter_id']}' 
										");
			$email_data=$email_res->fetch_assoc();
			$cinema_name="Shoreline Cinema Waikanae";
			include($config['phpmailer_dir']."class.phpmailer.php");
			$mail = new PHPMailer();
			$mail->IsSMTP;
			$mail->SMTPAuth = true;
			$mail->SMTPSecure = 'tls';
			$mail->Host = $config['newsletter_smtp_server'];
			$mail->Mailer = "smtp";
			$mail->Port = 587;
			$mail->Username = $config['newsletter_email'];
			$mail->Password = $config['newsletter_email_pass'];
			$mail->Subject = $email_data['subject'];
			$mail->From	= $config['newsletter_email'];
			$mail->FromName	= $cinema_name;
			$mail->Body	= $message_html;
			$mail->AltBody = $message_text;
			$mail->AddAddress($_REQUEST['test_recipient']);
			if ($mail->Send()) {
				header("Location: newsletters.php?complete=test&show=preview&newsletter_id={$_REQUEST['newsletter_id']}");
				exit;
			} else {
				header("Location: newsletters.php?show=preview&er=Cannot+send+test,+please+try+again.&newsletter_id={$_REQUEST['newsletter_id']}");
				exit;
			}
		}
	}
	
	if (isset($_POST['goto']) && $_POST['goto']=='preview') {
		// Update database
		$date_from=$_POST['y']."-".$_POST['m']."-".$_POST['d'];
		if (isset($_REQUEST['newsletter_id']) && $newsletter_id=$_REQUEST['newsletter_id']) {
			$sql = "UPDATE newsletters SET ";
		} else {
			$sql = "INSERT INTO newsletters SET ";
		}
		$movies = "";
		if (isset($_REQUEST['add_sessions'])) {
			$movies_array = $_REQUEST['add_sessions'];
			for ($n = 0; $n < count($movies_array); $n++) {
				if ($n > 0) {
					$movies .= ", ";
				}
				$movies.=$movies_array[$n];
			}
		}
		$sql .= "
			template_id='".$mysqli->real_escape_string($_POST['template_id'])."',
			sessions_start_date='".$mysqli->real_escape_string($date_from)."',
			sessions_num='".$mysqli->real_escape_string($_POST['sessions_num'])."',
			movies='".$mysqli->real_escape_string($movies)."',
			subject='".$mysqli->real_escape_string($_POST['subject'])."',
			editorial='".$mysqli->real_escape_string($_POST['editorial'])."',
			reply_address='".$mysqli->real_escape_string($_POST['reply_address'])."',
			status='new'
		";
		if (isset($newsletter_id)) {
			$sql .= " WHERE newsletter_id='$newsletter_id'";
			query($sql);
		} else {
			query($sql);
			$newsletter_id = $mysqli->insert_id;
		}
		// Load preview page
		header("Location: newsletters.php?show=preview&newsletter_id=$newsletter_id");
		exit;
	}

	if (isset($_REQUEST['newsletter_id'])) {
		$news_res = $mysqli->query("SELECT * FROM newsletters WHERE newsletter_id='{$_REQUEST['newsletter_id']}'");
		$news_data = $news_res->fetch_assoc();
	}
}
?>
<!DOCTYPE HTML>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Send and Track Email Newsletters</title>
		<link href="inc/css/bootstrap.min.css" rel="stylesheet" type="text/css">
		<link href="inc/css/dashboard.css" rel="stylesheet">
	</head>
	<body>
		<?php include("inc/header.inc.php");?>
		<div class="container-fluid">
			<div class="row">
				<?php include("inc/nav.inc.php");?>
			<?php if (check_cinema() && has_permission('newsletters')) { ?>
				<main role="main" class="col-md-9 ml-sm-auto col-lg-12 pt-3 px-4">
					<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
						<h1 class="h2">Bulk Email Newsletters</h1>
					</div>
					<?php echo check_msg(); ?>
						<form name="newsletter" action="newsletters.php" method="post" enctype="multipart/form-data">
					  <?php if (isset($_REQUEST['complete']) && $_REQUEST['complete']=='scheduled') { ?>
								<p>Your newsletter has successfully been scheduled for sending.</p>
				      <?php } else if (isset($_REQUEST['show']) && $_REQUEST['show']=='preview') { 
								if (isset($_REQUEST['complete']) && $_REQUEST['complete']=='test') { ?>
									<p>Your test email was sent successfully.</p>
						  <?php } ?>
								<strong>Email Preview:</strong>
								<br>
								<div style="overflow-x:auto;">
									<iframe src="<?php echo $config['cinema_url']?>email-newsletter.php?newsletter_id=<?php echo $_REQUEST['newsletter_id']?>" width="700" height="500" title="Preview" scrolling="yes" style="overflow:auto;">
										<p>Your browser does not seem to support this preview function.<br>It is recommended that you use Internet Explorer 5 or higher.</p>
										<p><a href="<?php echo $config['cinema_url']?>email-newsletter.php?newsletter_id=<?php echo $_REQUEST['newsletter_id']?>" target="_blank">Try clicking here to preview your email in a new window.</a></p>
									</iframe>
								</div>
								<br>
								<br>
								<?php
								$list_res = $mysqli->query("SELECT COUNT(*) AS num FROM users WHERE status = 'ok'");
								$list_data = $list_res->fetch_assoc();
								$list_num = $list_data['num']; ?>
								<input type="radio" name="recipients" value="all" id="recipients_all">
								<label for="recipients_all"> 
									Send to all <?php echo $list_num?> subscribers
								</label>
								<br>
								<input type="radio" name="recipients" value="self" id="recipients_self" checked>
								<label for="recipients_self"> 
									Send a test to <input type="email" name="test_recipient" value="<?php echo $news_data['reply_address']?>">
								</label>
								<br>
								<br>
								Send on 
								<?php
									date_default_timezone_set('Pacific/Auckland');
									$d = date('j');
									$m = date('n');
									$y = date('Y');
								?>
								<select name="d">
							  <?php for($n=1;$n<=31;$n++) { ?>
										<option value="<?php echo $n?>" <?php echo ($d==$n)?"selected":""?>><?php echo $n?></option>
							  <?php } ?>
								</select>
								<select name="m">
							  <?php $months = array(1=>"January","February","March","April","May","June","July","August","September","October","November","December");
									for($n=1;$n<=count($months);$n++) { ?>
										<option value="<?php echo $n?>" <?php echo ($m==$n)?"selected":""?>><?php echo $months[$n]?></option>
							  <?php } ?>
								</select>
								<select name="y">
								<?php $years = array(date('Y')-1,date('Y'),date('Y')+1);
									for($n=0;$n<count($years);$n++) { ?>
										<option value="<?php echo $years[$n]?>" <?php echo ($y==$years[$n])?"selected":""?>><?php echo $years[$n]?></option>
							  <?php } ?>
								</select>
								at
								<select name="t">
								<?php 
									$base_time = strtotime('2012-01-01 00:00:00');
									$minutes_now = strtotime('2012-01-01 ' . date('H:i:s'));
									$steps = 10*60;
									$selected = false;
									for($n=0; $n<24*60*60; $n+=$steps) {
										$minutes = $base_time + $n;
										if (!$selected && $minutes > $minutes_now) {
											$selected = $minutes;
										} ?>
										<option value="<?php echo date('H:i:s', $minutes)?>"<?php echo ($selected == $minutes)?' selected="selected"':''?>><?php echo date('g:i a', $minutes)?></option>
							  <?php } ?>
								</select>
								<br>
								<br>
								<input type="hidden" name="newsletter_id" value="<?php echo $_REQUEST['newsletter_id']?>">
								<input type="hidden" name="goto" value="send">
								<input type="submit" name="goto" class="btn btn-warning submit" value="Amend">
								<input type="submit" name="submit" class="btn btn-success submit" value="Send"></p>
							</form>
					  <?php } else { 
								if (!isset($_REQUEST['newsletter_id'])) {
									$t = strtotime("next Thursday");
									$d=date('j', $t);
									$m=date('n', $t);
									$y=date('Y', $t);
								} else {
									$sessions_start_date=explode('-',$news_data['sessions_start_date']);
									$d=$sessions_start_date[2];
									$m=$sessions_start_date[1];
									$y=$sessions_start_date[0];
									$movies=explode(", ",$news_data['movies']);
								} ?>
								<table cellpadding="0" cellspacing="0" border="0">
									<tr>
										<td>
											<strong>Subject:</strong>
										</td>
										<td>&nbsp; &nbsp;</td>
										<td>
											<input 
												name="subject" 
												type="text" 
												size="30" 
												maxlength="50" 
												value="<?php echo (isset($news_data['subject']))?$news_data['subject']:$config['cinema_name']." Session Times"?>"
											>
										</td>
									</tr>
									<tr>
										<td>
											<strong>From:</strong>
										</td>
										<td>&nbsp; &nbsp;</td>
										<td>
											<input 
												name="reply_address" 
												type="text" 
												size="30" 
												maxlength="50"
												value="
<?php if (!empty($news_data['subject'])) {
	if (!empty($news_data['reply_address'])) {
		echo $news_data['reply_address'];
	} else {
		echo $cinema_data['newsletter_email'];
	}
} elseif (!empty($cinema_data['newsletter_email'])) {
	echo $cinema_data['newsletter_email'];
} else {
	echo $config['newsletter_email'];
}
?>
												"
											>
										</td>
									</tr>
									<tr>
										<td>
											<strong>Template:</strong>
										</td>
										<td>&nbsp; &nbsp;</td>
										<td>
											<select name="template_id">
										  <?php $template_res = $mysqli->query("SELECT template_id, name FROM newsletter_templates");
												while ($template_data = $template_res->fetch_assoc()) { ?>
													<option 
														value="<?php echo $template_data['template_id']?>" 
														<?php echo (isset($news_data['template_id']) && $news_data['template_id']==$template_data['template_id'])?"selected":""?>
													>
														<?php echo $template_data['name']?>
													</option>
										  <?php } ?>
											</select>
										</td>
									</tr>
								</table>
								<p>
									<strong>Editorial:</strong>
									<br>
							  <?php if (has_permission('newsletter_merge')) { ?>
										<em>You have e-mail-merge facilities activated so your emails can be personalised for each subscriber:</em>
										<br>
										&bull;&nbsp; If you place [first_name] in your editorial, it will be replaced with the recipient's first name.
										<br>
										&bull;&nbsp; If you place [last_name] in your editorial, it will be replaced with the recipient's last name.
										<br><br>
							  <?php }
									if (isset($_POST['editorial'])) {
										$editorial=$_POST['editorial'];
									} else if (isset($news_data['editorial'])) {
										$editorial=$news_data['editorial'];
									} else {
										$editorial='';
									}
									$editor_name = 'editorial';
									$editor_value = $editorial;
									include('inc/tiny_mce/load.php'); ?>
								</p>
								<p>
									<strong>Session Times:<br></strong>
									Show 
									<input 
										name="sessions_num" 
										type="text" 
										value="<?php echo (isset($news_data) && $news_data['sessions_num'])?$news_data['sessions_num']:"7"?>" 
										size="2" 
										maxlength="2"
									> 
									days worth of session times, starting from 
									<select name="d">
								  <?php for($n=1;$n<=31;$n++) { ?>
											<option value="<?php echo $n?>" <?php echo ($d==$n)?"selected":""?>>
												<?php echo $n?>
											</option>
								  <?php } ?>
									</select>
									<select name="m">
								  <?php $months=array(1=>"January","February","March","April","May","June","July","August","September","October","November","December");
										for($n=1;$n<=count($months);$n++) { ?>
											<option value="<?php echo $n?>" <?php echo ($m==$n)?"selected":""?>>
												<?php echo $months[$n]?>
											</option>
								  <?php } ?>
									</select>
									<select name="y">
								  <?php $years=array(date('Y')-1,date('Y'),date('Y')+1);
										for($n=0;$n<count($years);$n++) { ?>
											<option value="<?php echo $years[$n]?>" <?php echo ($y==$years[$n])?"selected":""?>>
												<?php echo $years[$n]?>
											</option>
								  <?php } ?>
									</select>
								</p>
								<p>
									<strong>Include session times for these movies (if available):</strong><br>
									<table cellpadding="0" cellspacing="0" border="0">
										<tr>
											<td valign="top">
											<?php 
												$sql="
													SELECT m.movie_id, m.title 
													FROM movies m
													INNER JOIN sessions st 
														ON m.movie_id=st.movie_id 
													WHERE st.time>=CURDATE() 
													GROUP BY m.movie_id
													ORDER BY m.title
												";
												$session_res=$mysqli->query($sql) or user_error("Gnarly: $sql");
												$num_sessions=$session_res->num_rows;
												if ($num_sessions>0) {
													$n=0;
													while ($session_data=$session_res->fetch_assoc()) { 
														if ($n>=$num_sessions/2) { echo "</td><td>&nbsp; &nbsp;</td><td valign='top'>"; $n=0; } ?>
														<input 
															name="add_sessions[]" 
															type="checkbox" 
															value="<?php echo $session_data['movie_id']?>" 
															class="inline_input" 
															id="movie<?php echo $session_data['movie_id']?>" 
															<?php echo (!isset($movies) || in_array($session_data['movie_id'],$movies))?"checked":""?>
														>
														<label for="movie<?php echo $session_data['movie_id']?>">
															<?php echo summary($session_data['title'],35,$round='char')?>
														</label>
														<br>
												  <?php $n++; 
													} 
												} else { ?>
													No session times currently listed.
										  <?php } ?>
											</td>		
										</tr>		
									</table>
								</p>
								<p>
							  <?php if (isset($_REQUEST['newsletter_id'])) { ?>
										<input type="hidden" name="newsletter_id" value="<?php echo $_REQUEST['newsletter_id']?>">
							  <?php } ?>
									<input type="hidden" name="goto" value="preview">
								</p>
						</form>
						<div class="form-group"> 
							<button name="submit" class="btn btn-primary submit" onclick="uploadImagesTinyMCE();"submit">Preview</button>
						</div>
					<?php } ?>
			  <?php } else { ?>
					<main role="main" class="col-md-9 ml-sm-auto col-lg-12 pt-3 px-4">
						<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
							<h1 class="h2">Bulk Email Newsletters</h1>
						</div>
						<p><?php check_notice("Either you are not logged in or you do not have permission to use this feature.") ?></p>
						<p>This page gives access to our automated email marketing system exclusively developed for New Zealand cinemas. You can send personalised, branded, formatted,  bulk HTML email messages to your website members. Session times and movie details are automatically gathered from your movie list and embedded in your emails, potentially saving your hours of work.</p>
			  <?php } ?>
	  <?php include("inc/footer.inc.php") ?>
	</body>
</html>
