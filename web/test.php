<?php
//require('inc/web.inc.php');
//require('inc/smarty_vars.inc.php');
require('/var/www/Cinemanager/_cron/cinemaemails.php');

/*require($config['phpmailer_dir']."class.phpmailer.php");

$subject = "Hello";
$message = "This is a test email";

$mail = new PHPMailer(true);
$mail->isSMTP();
$mail->SMTPDebug = 3;
$mail->SMTPAuth = true;
$mail->SMTPSecure = 'tls';
$mail->Host = $config['smtp_server'];
$mail->Mailer = "smtp";
$mail->Port = 587;
$mail->Username = $config['booking_send_email'];
$mail->Password = $config['booking_password'];
$mail->setFrom($config['booking_send_email'], "{$cinema_data['cinema_name']} Website");
$mail->addAddress($cinema_data['booking_email']);
$mail->FromName	= $cinema_data['name'];
$mail->Subject = $subject;
$mail->Body = $message;
echo '=================';
echo print_r($mail);
echo '=================';
if ($mail->Send()) {
	$mail->clearAddresses();
	echo "================= Successfully sent message =================";
} else {
	echo "================= Failed to send message =================";
}*/
?>
