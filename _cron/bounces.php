#!/usr/bin/php7.2
<?php

$root_dir = dirname(dirname(__FILE__));
require($root_dir."/functions.inc.php");
require($root_dir."/config.inc.php");

ini_set('memory_limit','512M');

$hostname = '{'.$config['newsletter_smtp_server'].':993/imap/ssl}INBOX';
$username = $config['newsletter_email'];
$password = $config['newsletter_email_pass'];
$inbox = imap_open($hostname,$username,$password) or die('Cannot connect to mailbox: ' . imap_last_error());

$failed = imap_search($inbox,'FROM "MAILER-DAEMON@mail.shoreline.nz" BODY "Action: failed"');
$delete_codes = ['5.5.0', '5.1.1', '5.4.4']; // 5.4.4: host not found
$rejected_codes = ['5.0.0', '5.2.2', '5.7.1'];
$office_banned_codes = ['5.7.606'];

if ($failed) {
	error_log("[BEGIN], ".date('Y-m-d').": Updating email addresses...\n",3,"/var/www/logs/error-email.log");
	rsort($failed);
	$n = 0;
	foreach ($failed as $email) {
		$overview = imap_fetch_overview($inbox, $email, 0);
		$message = imap_body($inbox, $email);
		$status_pattern = '/Status: ([^\s]*)/';
		$matches = Array();
		preg_match($status_pattern, $message, $matches);

		if (in_array($matches[1], $delete_codes)) {
			$status = 'nonexistent';
		} else if (in_array($matches[1], $rejected_codes)) {
			$status = 'rejected';
		} else if (in_array($matches[1], $office_banned_codes)) {
			$status = 'ok';
			error_log("[ERROR]: Banned from sending, visit sender.office.com to fix.\n",3,"/var/www/logs/error-email.log");
		} else {
			$status = 'bounced';
		}

		$pattern = '/Final-Recipient: rfc822; (.*)/';
                $matches = Array();
                preg_match($pattern, $message, $matches);
                $email_addr = $matches[1];
                $email_addr = str_replace("\r","",$email_addr);
                $sql = "
                        UPDATE users
                        SET status = '".$status."'
                        WHERE email = '".$mysqli->real_escape_string($email_addr)."'
                ";
                $mysqli->query($sql) or error_log("[ERROR]: Can't set status to '".$status."', SQL: ".$sql."\n",3,"/var/www/logs/error-email.log");
                imap_delete($inbox,$overview[0]->msgno) or error_log("[WARN]: Can't delete email from inbox.\n",3,"/var/www/logs/error-email.log");
		error_log("----- ".$status." ".$email_addr.".\n",3,"/var/www/logs/error-email.log");
		$n += 1;
	}
	error_log("[FINISH], ".date('Y-m-d').": Updated ".$n."/".count($failed)." email addresses.\n",3,"/var/www/logs/error-email.log");
} else {
	error_log("[INFO], ".date('Y-m-d').": No email addresses to update.\n",3,"/var/www/logs/error-email.log");
}


imap_expunge($inbox);
imap_close($inbox);

?>
