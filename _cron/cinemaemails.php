#!/usr/bin/php
<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

//send out scheduled emails / newsletters from cinemas
$root_dir = dirname(dirname(__FILE__)).'/';
require($root_dir."config.inc.php");
require($root_dir."functions.inc.php");
require($config['phpmailer_dir']."class.phpmailer.php");

//prepare variables
$total_sends=0;
$maximum_sends=1000; //per newsletter

//get list of all pending newsletters
$sql = "
	SELECT *
	FROM newsletters 
	WHERE status='pending'
	ORDER BY send_after ASC
";
$newsletters_res=$mysqli->query($sql) or user_error($sql);
if ($newsletters_res->num_rows >= 1) {
	while ($newsletters=$newsletters_res->fetch_assoc()) {
		//prepare message basics
		$sql = "
			SELECT name,city,id,timezone
			FROM settings
			WHERE id=1
		";
		$cinema_res=$mysqli->query($sql) or user_error($sql);
		$cinema_data=$cinema_res->fetch_assoc();
		$cinema_data['timezoneOffset'] = differenceBetweenTimezones($cinema_data['timezone']);
		//check that this newsletter is scheduled to be sent based on the right timezone
		$sql = "
			SELECT newsletter_id
			FROM newsletters 
			WHERE newsletter_id='{$newsletters['newsletter_id']}'
				AND send_after <= DATE_ADD(NOW(), INTERVAL {$cinema_data['timezoneOffset']} HOUR)
		";
		$tmpRes = $mysqli->query($sql);
		if ($tmpRes->num_rows==1) {
			$orig_html=$newsletters['message_html'];
			$orig_text=$newsletters['message_text'];
			//get list of recipients for this newsletter
			$sql="
				SELECT u.email,u.user_id,u.first_name,u.last_name,
					u.plain_text
				FROM users u
				WHERE CHAR_LENGTH(u.email) > 5
			";
			//some cinemas require that users have status=ok (confirmed email address)
			//if (has_permission('signup_confirmation',$newsletters['cinema_id'])) {
			//	$sql.=" AND u.status='ok'";
			//}
			//complete query
			$sql.=" GROUP BY u.user_id LIMIT $maximum_sends";
			$recipients_res=$mysqli->query($sql) or user_error($sql);
			//if there are no recipients, mark this newsletter as sent
			if ($recipients_res->num_rows==0) {
				$sql = "
					UPDATE newsletters 
					SET status='sent' 
					WHERE newsletter_id = '{$newsletters['newsletter_id']}'
				";
				$mysqli->query($sql) or user_error($sql);
			//otherwise send to selected recipients
			} else {
				while ($recipients=$recipients_res->fetch_assoc()) {
					unset($message_html,$message_text);
					//compile new message for user
					$subject=$newsletters['subject'];
					$from_name=(strpos($cinema_data['name'], $cinema_data['city'])) ? $cinema_data['name'] : $cinema_data['name'] . ' ' . $cinema_data['city'];
					$from_email=$newsletters['reply_address'];
					$to_email=$recipients['email'];
					//generate unsubscribe link
					$num1=$recipients['user_id'];
					$encoded=encode($num1);
					$unsub_link="{$config['cinema_url']}e/link.php?u={$num1}&n={$newsletters['newsletter_id']}&un={$encoded}";
					//prepare plain text version
					$message_text=$orig_text."\r\n\r\n________________________________________\r\nTo unsubscribe from these emails, please visit this web page:\r\n$unsub_link";
					//prepare html version
					if ($recipients['plain_text']!=1) {
						$message_html=str_replace("<!--unsub-->",$unsub_link,$orig_html);
					}
					//add user id to tracked links
					$message_html=str_replace('[MMUID]',$num1,$message_html);
					$message_text=str_replace('[MMUID]',$num1,$message_text);
					//handle mail merge
					if ($newsletters['merge']==1) {
						$merge_from=array('[first_name]','[last_name]');
						$merge_to=array($recipients['first_name'],$recipients['last_name']);
						$message_text=str_replace($merge_from,$merge_to,$message_text);
						if ($recipients['plain_text']!=1) {
							$message_html=str_replace($merge_from,$merge_to,$message_html);
						}
					}
					//add beacon
					$beacon_url = "{$config['cinema_url']}e/beacon-{$newsletters['newsletter_id']}-$num1.gif";
					$beacon_html = "<img src='{$beacon_url}' width='1' height='1'>";
					$message_html = str_ireplace("</body>", "$beacon_html</body>", $message_html);
					//send email
					$token = md5('cm'.$num1.microtime());
					$mail = new PHPMailer();
					$mail->IsSMTP;
					$mail->SMTPAuth = true;
					$mail->SMTPSecure = 'tls';
					$mail->Host = $config['smtp_server'];
					$mail->Mailer = "smtp";
					$mail->Port = 587;
					$mail->Username = $config['sessions_email'];
					$mail->Password = $config['booking_password'];
					$mail->Subject = $subject;
					$mail->From = $from_email;
					$mail->FromName	= $from_name;
					$mail->ReturnPath = $config['bounce_email'];
					$mail->AddCustomHeader("X-CinemanagerToken: $token");
					if ($recipients['plain_text']==1) {
						$mail->Body = $message_text;
					} else {
						$mail->Body = $message_html;
						$mail->AltBody = $message_text;
					}
					$mail->AddReplyTo($from_email, $from_name);
					$mail->AddAddress($to_email);
					if (!$mail->Send()) {
						die("Could not send.");
					}
					//log to database
					$sql = "
						INSERT INTO newsletter_log 
						SET newsletter_id = '{$newsletters['newsletter_id']}', 
							user_id = '{$recipients['user_id']}',
							token = '{$token}'
					";
					$mysqli->query($sql) or user_error($sql);
					$mail->ClearAddresses();
					$total_sends++;
				}
			}
		}
	}
}

?>
