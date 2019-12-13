#!/usr/bin/php
<?php

$root_dir = dirname(dirname(__FILE__));
require($root_dir."/functions.inc.php");
require($root_dir."/config.inc.php");

ini_set('memory_limit','512M');

$hostname = '{'.$config['newsletter_smtp_server'].':993/imap/ssl}INBOX';
$username = $config['newsletter_email'];
$password = $config['newsletter_email_pass'];

$inbox = imap_open($hostname,$username,$password) or die('Cannot connect to mailbox: ' . imap_last_error());
$emails = imap_search($inbox,'SUBJECT "Undelivered Mail Returned to Sender"');


if($emails){
rsort($emails);
	foreach($emails as $email){
        	$overview = imap_fetch_overview($inbox,$email,0);
        	$message = imap_body($inbox, $email);
        	$pattern = '/Final-Recipient: rfc822; (.*)/';
    		$matches = Array();
    		preg_match($pattern, $message, $matches);
    		$email_addr = $matches[1];
		$email_addr = str_replace("\r","",$email_addr);
		$sql = "
			UPDATE users
			SET status = 'bounced'
			WHERE email = '".$mysqli->real_escape_string($email_addr)."'
		";
		$mysqli->query($sql) or error_log("[ERROR]: Can't set status to bounced, SQL: ".$sql."\n",3,"/var/www/logs/error-email.log");
		imap_delete($inbox,$overview[0]->msgno) or error_log("[WARN]: Can't delete email from inbox.\n",3,"/var/www/logs/error-email.log");
	}
	error_log("[OK]: Finished updating bounced users.\n",3,"/var/www/logs/error-email.log");
} else {
	error_log("[INFO]: No new bounces.\n",3,"/var/www/logs/error-email.log");
}

imap_expunge($inbox);
imap_close($inbox);

?>
