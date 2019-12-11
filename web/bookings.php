<?php
require('inc/web.inc.php');
require('inc/smarty_vars.inc.php');
require($config['phpmailer_dir']."class.phpmailer.php");

$smarty->caching = 0;
$tpl_name = 'bookings.tpl';
$tpl = $config['cinema_dir'].'tpl/'.$tpl_name;

if(!$smarty->isCached($tpl)) {

	if (isset($_REQUEST['booking_id']) && has_permission('bookings')) {
		
		// Assign page variables
		$smarty->assign('domain',$cinema_domain);
		$smarty->assign('name',$cinema_data['cinema_name']);
		$smarty->assign('city',$cinema_data['city']);
		$smarty->assign('booking_id',$_REQUEST['booking_id']);
		$smarty->assign('gacode',$config['ga_code']);
		
		global $mysqli;
		// Get session data
		$sql = "
			SELECT movie_id
			FROM sessions
			WHERE session_id = '" . $mysqli->real_escape_string($_REQUEST['booking_id']) . "'
			LIMIT 1
		";
		$res = query($sql);
		if ($res->num_rows) {
			$data = $res->fetch_assoc();
			$movie_id = $data['movie_id'];
			$movie_data = get_movie($movie_id,false,NULL,'medium');
			$session = get_session($_REQUEST['booking_id']);
			if (is_array($movie_data) && $session) {
				
				$smarty->assign($movie_data); 
				$smarty->assign('movie_id',$movie_id); 
				$smarty->assign('session',$session); 
				
				// Booking complete comfirmation
				if (isset($_GET['booking']) && $_GET['booking']=='complete') {
					$smarty->assign('booking','complete');
					$smarty->assign('t_adults',(isset($_GET['ta']))?$_GET['ta']:0);
					$smarty->assign('t_children',(isset($_GET['tc']))?$_GET['tc']:0);
					$smarty->assign('t_seniors',(isset($_GET['ts']))?$_GET['ts']:0);
					$smarty->assign('t_students',(isset($_GET['tu']))?$_GET['tu']:0);
				// Booking failed message
				} elseif (isset($_GET['booking']) && $_GET['booking']=='failed') {
					$smarty->assign('booking','failed');
					$smarty->assign('t_adults',(isset($_GET['ta']))?$_GET['ta']:0);
					$smarty->assign('t_children',(isset($_GET['tc']))?$_GET['tc']:0);
					$smarty->assign('t_seniors',(isset($_GET['ts']))?$_GET['ts']:0);
					$smarty->assign('t_students',(isset($_GET['tu']))?$_GET['tu']:0);
				
				// Booking display and process
				} else {
					$smarty->assign('ticket_nums', array(0,1,2,3,4,5,6,7,8,9,10));
					$smarty->assign('movie_list',get_movie_list_full('ns','m.title',0,'%W %D','%e %b',100,'today',null,null,true,'medium'));
					// Send booking request
					if (isset($_POST['action']) && $_POST['action']=='place_booking') {
						// Send booking email
						if (
							isset($_POST['c_name']) && strlen($_POST['c_name'])>3 && 
							isset($_POST['c_email']) && is_email($_POST['c_email']) && 
							isset($_POST['c_phone']) && strlen($_POST['c_phone'])>3 && (
								(isset($_POST['t_adults']) && $_POST['t_adults']!=0) || 
								(isset($_POST['t_children']) && $_POST['t_children']!=0) || 
								(isset($_POST['t_seniors']) && $_POST['t_seniors']!=0) || 
								(isset($_POST['t_students']) && $_POST['t_students']!=0)
							)
						) {
							$message="The following booking was made via the {$cinema_data['name']} {$cinema_data['city']} website on ".date('l, j M Y \a\t g:ia')."\n";
							$message.="\n";
							$message.="Customer Name = {$_POST['c_name']}";
							if (isset($_POST['c_wheelchair'])) {
								$message.=" - [Wheelchair Access]";
							}
							$message.="\n";
							$message.="Customer Phone = {$_POST['c_phone']}\n";
							$message.="Customer Email = {$_POST['c_email']}\n";
							$message.="\n";
							$message.="Movie Title = {$movie_data['movie']['title']}\n";
							$message.="Movie Date = " . date('l j F',strtotime($session['session_timestamp'])) . "\n";
							$message.="Movie Session = {$session['session_time']}\n";
							$message.="\n";
							if (isset($_POST['t_adults'])) { $message.="Adult Tickets = {$_POST['t_adults']}\n"; }
							if (isset($_POST['t_children'])) { $message.="Child Tickets = {$_POST['t_children']}\n"; }
							if (isset($_POST['t_seniors'])) { $message.="Senior Tickets = {$_POST['t_seniors']}\n"; }
							if (isset($_POST['t_students'])) { $message.="Student Tickets = {$_POST['t_students']}\n"; }
							$message.="\n";
							$subject="{$cinema_data['name']} {$cinema_data['city']} Online Booking";
							$subject.=" from {$_POST['c_email']}";
							//$to="{$cinema_data['cinema_name']} <{$cinema_data['booking_email']}>";
							//$headers="From: {$cinema_data['cinema_name']} Website <{$cinema_data['booking_email']}>";
							//mail($to,$subject,$message,$headers);
							$mail = new PHPMailer();
							$mail->IsSMTP();
							$mail->SMTPAuth = true;
							$mail->SMTPSecure = 'tls';
							$mail->Host = $config['booking_smtp_server'];
							$mail->Mailer = "smtp";
							$mail->Port = 587;
							$mail->Username = $config['booking_send_email'];
							$mail->Password = $config['booking_email_pass'];
							//$mail->AddReplyTo($config['reply_email'], 'Shoreline Crew');
							$mail->From = $config['booking_send_email'];
							$mail->addAddress($config['booking_receive_email']);
							$mail->FromName	= $cinema_data['name']." Website";
							$mail->Subject = $subject;
							$mail->Body = $message;
							//die(print_r($mail));
							if($mail->Send()) {
								$mail->ClearAddresses();

								// Log booking
								if (has_permission('log_bookings')) {
									$sql = "
										INSERT INTO booking_log
										SET name = '" . $mysqli->real_escape_string($_POST['c_name']) . "',
										email = '" . $mysqli->real_escape_string($_POST['c_email']) . "',
										session_id = '" . $mysqli->real_escape_string($_REQUEST['booking_id']) . "',
									";
									if (isset($_POST['t_adults'])) {
										$sql .= " adults = '" . $mysqli->real_escape_string($_POST['t_adults']) . "',";
									}
									if (isset($_POST['t_children'])) {
                                                                                $sql .= " children = '" . $mysqli->real_escape_string($_POST['t_children']) . "',";
                                                                        }
									if (isset($_POST['t_seniors'])) {
                                                                                $sql .= " seniors = '" . $mysqli->real_escape_string($_POST['t_seniors']) . "',";
                                                                        }
									if (isset($_POST['t_students'])) {
                                                                                $sql .= " students = '" . $mysqli->real_escape_string($_POST['t_students']) . "',";
                                                                        }
									if (isset($_POST['c_wheelchair'])) {
                                                                                $sql .= " wheelchair = '1',";
                                                                        }
									$sql .= "phone = '" . $mysqli->real_escape_string($_POST['c_phone']) . "'";
									$mysqli->query($sql) or user_error("Error at: ".$sql);
								}

							    // Email the customer back
							    if (has_permission('email_booking_reply')) {
								$message_cust="Dear {$_POST['c_name']}\n";
								$message_cust.="\n";
								$message_cust.="Thank you for placing your booking through the {$cinema_data['name']} website \r\nWe have received your request and will be in touch shortly to confirm your booking.\n";
								$message_cust.="\n";
								$message_cust.="Please note that this e-mail is not a confirmation of your booking, as seats are subject to availability.\n";
								$message_cust.="\n";
								$message_cust.="We look forward to seeing you.\n";
								$message_cust.="\n";
								$message_cust.=$cinema_data['name'].",\n";
								$message_cust.=$cinema_data['city']."\n";
								$message_cust.="\n";
								$message_cust.="----------------------------------------\n";
								$message_cust.="\n";
								$message_cust.=$message;
								$subject="{$cinema_data['name']} {$cinema_data['city']} Online Booking";
								//$to="{$_POST['c_name']} <{$_POST['c_email']}>";
								//$headers="From: {$cinema_data['cinema_name']} <{$cinema_data['booking_email']}>";
								//mail($to,$subject,$message_cust,$headers);
								$reply = new PHPMailer();
								$reply->IsSMTP();
								$reply->SMTPAuth = true;
								$reply->SMTPSecure = 'tls';
								$reply->Host = $config['booking_smtp_server'];
								$reply->Mailer = "smtp";
								$reply->Port = 587;
								$reply->Username = $config['booking_send_email'];
								$reply->Password = $config['booking_email_pass'];
								$reply->Subject = $subject;
								$reply->AddReplyTo($config['reply_email'], 'Shoreline Crew');
								$reply->From = $config['booking_send_email'];
								$reply->FromName = $cinema_data['name']." ".$cinema_data['city'];
								$reply->Body = $message_cust;
								$reply->AddAddress($_POST['c_email']);
								$reply->Send();
								$reply->ClearAddresses();
							    }
							    // Redirect
							    $url = "/bookings/{$_REQUEST['booking_id']}/";
							    $url .= "/complete/?";
							    $url .= (isset($_POST['t_adults'])) ? "&ta={$_POST['t_adults']}" : "" ;
							    $url .= (isset($_POST['t_children'])) ? "&tc={$_POST['t_children']}" : "" ;
							    $url .= (isset($_POST['t_seniors'])) ? "&ts={$_POST['t_seniors']}" : "" ;
							    $url .= (isset($_POST['t_students'])) ? "&tu={$_POST['t_students']}" : "" ;
							    header("Location: $url");
							    exit;
							} else {
							    $url = "/bookings/{$_REQUEST['booking_id']}/";
							    $url .= "/failed/?";
							    $url .= (isset($_POST['t_adults'])) ? "&ta={$_POST['t_adults']}" : "" ;
							    $url .= (isset($_POST['t_children'])) ? "&tc={$_POST['t_children']}" : "" ;
							    $url .= (isset($_POST['t_seniors'])) ? "&ts={$_POST['t_seniors']}" : "" ;
							    $url .= (isset($_POST['t_students'])) ? "&tu={$_POST['t_students']}" : "" ;
							    header("Location: $url");
							    exit; 
							}
						// Return with variables and error message
						} else {
							$smarty->assign('er','incomplete');
							$smarty->assign('c_name',$_POST['c_name']);
							$smarty->assign('c_email',$_POST['c_email']);
							$smarty->assign('c_phone',$_POST['c_phone']);
							$smarty->assign('c_wheelchair',(isset($_POST['c_wheelchair']))?'checked':'');
							$smarty->assign('t_adults',(isset($_POST['t_adults']))?$_POST['t_adults']:'');
							$smarty->assign('t_children',(isset($_POST['t_children']))?$_POST['t_children']:'');
							$smarty->assign('t_seniors',(isset($_POST['t_seniors']))?$_POST['t_seniors']:'');
							$smarty->assign('t_students',(isset($_POST['t_students']))?$_POST['t_students']:'');
						}
					}
				}
			}
		} else {
			header("Location: ".$cinema_domain."404/");
		}
	}
	
	// Common functions
	include('inc/local.inc.php');
}

//include template
$smarty->display($tpl);

?>
