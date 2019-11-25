<?php
require('inc/web.inc.php');
require('inc/smarty_vars.inc.php');

$tpl_name = 'contact-us.tpl';
$tpl = $config['site_dir'].'tpl/generic.tpl';
$cache_id = 'contact-us';

if(!$smarty->isCached($tpl,$cache_id)) {

	// Assign page variables
	$smarty->assign('domain',$cinema_domain);
	$smarty->assign('name',$cinema_data['cinema_name']);
	$smarty->assign('city',$cinema_data['city']);
	$smarty->assign('movie_image_url',$global['movie_image_url']);
	$smarty->assign('movie_image_url_secure',$global['movie_image_url_secure']);
	$smarty->assign('tpl_name',$tpl_name);
	
	if (has_permission('edit_pages')) {
		$smarty->assign('page',get_page_content('contact-us'));
	}

	// Common functions
	include('inc/local.inc.php');
	
	// Register functions / filters
	//$smarty->registerPlugin("function", "summary", "smarty_summary");
	//$smarty->registerFilter("pre", "edit_image_path");

}

//process enquiry form
/*if (isset($_POST['action']) && $_POST['action']=='send_enquiry' && isset($_POST['subject'])) {
	check_referrer();
	
	//validation
	if (isset($recaptchaPublicKey)) {
		if (!isset($_POST['recaptcha_response_field']) && !isset($_POST['recaptcha_challenge_field'])) {
			$errorMessage = 'Please enter the words from the security image.';
		} else {
			$resp = recaptcha_check_answer($recaptchaPrivateKey, $_SERVER["REMOTE_ADDR"], $_POST['recaptcha_challenge_field'], $_POST['recaptcha_response_field']);
			if (!$resp->is_valid) {
				$errorMessage = 'The security image text you entered was incorrect.'; //$errorMessage = $resp->error;
			}
		}
	}
	
	// ReCaptcha 2.0
	elseif ($cinema_id == 1046 || $cinema_id == 1171 || $cinema_id == 1172 || isset($_REQUEST['g-recaptcha-response'])) {
		if (!empty($_REQUEST['g-recaptcha-response'])) {
			$recaptchaUrl = "https://www.google.com/recaptcha/api/siteverify?secret=6LemUAITAAAAAFe1A60wIMRkdPM78DXqK7LfJk-e&response={$_REQUEST['g-recaptcha-response']}";
			$recaptchaResponse = json_decode(file_get_contents($recaptchaUrl));
			if ($recaptchaResponse->success == false) {
				$errorMessage = 'The security image text you entered was incorrect.';
			}
		} else {
			$errorMessage = 'The security image text you entered was incorrect.';
		}
	}
	
	if (empty($errorMessage)) {
		//create message
		$this_page=$_SERVER['PHP_SELF'];
		$this_qs=$_SERVER['QUERY_STRING'];
		if (!empty($_POST['first_name']) && !empty($_POST['last_name'])) {
			$_POST['name'] = $_POST['first_name'] . ' ' . $_POST['last_name'];
		}
		if ($_POST['name'] && $_POST['email'] && $_POST['request']) {
			if ($cinema_id == 1045) {
				switch ($_POST['subject']) {
					case 'something_else':
						$to = 'admin@baycitycinemas.co.nz';
						break;
					case 'vouchers':
					case 'corporate_gifts':
					case 'advertising':
						$to = 'sales@baycitycinemas.co.nz';
						break;
					case 'functions':
					case 'conferencing':
						$to = 'bookings@baycitycinemas.co.nz';
						break;
					case 'onine_booking':
					case 'complaint':
						$to = 'service@baycitycinemas.co.nz';
						break;
					case 'press':
					case 'website':
						$to = 'marketing@baycitycinemas.co.nz';
						break;
					case 'careers':
						$to = 'employment@baycitycinemas.co.nz';
						break;
					default:
						$cinema_data['email'];
						break;
				}
			} elseif (!empty($_POST['recipient']) && $test1 = explode('@', $_POST['recipient']) && $test2 = explode('@', $cinema_data['email']) && $test1[1] == $test2[1]) {
				$to = $_POST['recipient'];
			} else {
				$to = $cinema_data['email'];
			}
			if (in_array($cinema_id, array(1045, 1127)) && isset($_POST['location'])) {
				$subject = ucwords($_POST['location']).': '.ucwords(str_replace('_',' ',$_POST['subject']));
			} else {
				$subject = ucwords(str_replace('_',' ',$_POST['subject']));
				if (in_array($cinema_id, array(1171, 1172, 1046))) {
					$subject = $cinema_data['city'] . ' Enquiry: ' . $subject;
				}
			}
			$headers = "From: {$_POST['name']} <{$_POST['email']}>";
			$message="";
			//add optional fields
			if ($_POST['name']) {
				$message.="Name: {$_POST['name']}\n\n";
			}
			if ($_POST['surname']) {
				$message.="Surname: {$_POST['surname']}\n\n";
			}
			if ($_POST['email']) {
				$message.="Email: {$_POST['email']}\n\n";
			}
			if (isset($_POST['phone'])) {
				$message.="Phone: {$_POST['phone']}\n\n";
			}
			if (isset($_POST['mobile'])) {
				$message.="Mobile: {$_POST['mobile']}\n\n";
			}
			if (isset($_POST['date_of_birth'])) {
				$message.="Date of Birth: {$_POST['date_of_birth']}\n\n";
			}
			if (isset($_POST['fax'])) {
				if($_POST['fax'] == 'Fax'){
					$fax = 'N/A';
				} else {
					$fax = $_POST['fax'];
				}
				$message.="Fax: {$fax}\n\n";
			}
			if (isset($_POST['company'])) {
				$message.="Company: {$_POST['company']}\n\n";
			}
			if (isset($_POST['gender'])) {
				$message.="Gender: {$_POST['gender']}\n\n";
			}
			if (isset($_POST['street'])) {
				$message.="Street: {$_POST['street']}\n\n";
			}
			if (isset($_POST['suburb'])) {
				$message.="Suburb: {$_POST['suburb']}\n\n";
			}
			if (isset($_POST['city'])) {
				$message.="City: {$_POST['city']}\n\n";
			}
			if (isset($_POST['zip'])) {
				$message.="Zip: {$_POST['zip']}\n\n";
			}
			if (isset($_POST['post_code'])) {
				$message.="Post Code: {$_POST['post_code']}\n\n";
			}
			if (isset($_POST['country'])) {
				$message.="Country: {$_POST['country']}\n\n";
			}
			if (isset($_POST['location'])) {
				$message.="Location: {$_POST['location']}\n\n";
			}
			if (isset($_POST['date_Day']) && isset($_POST['date_Month']) && isset($_POST['date_Year'])) {
				$message.="Date: {$_POST['date_Day']}/{$_POST['date_Month']}/{$_POST['date_Year']}\n\n";	
			} elseif (isset($_POST['date'])) {
				$message.="Date: {$_POST['date']}\n\n";	
			}
			if (isset($_POST['time'])) {
				$message.="Time: {$_POST['time']}\n\n";
			}
			if (isset($_POST['people'])) {
				$message.="Number of People: {$_POST['people']}\n\n";
			}
			if (isset($_POST['purpose'])) {
				$message.="Main Purpose: {$_POST['purpose']}\n\n";
			}
			if (isset($_POST['film'])) {
				$message.="Film: {$_POST['film']}\n\n";
			}
			if (isset($_POST['tickets'])) {
				$message.="Tickets: {$_POST['tickets']}\n\n";
			}
			if (isset($_POST['type'])) {
				$message.="Type: {$_POST['type']}\n\n";
			}
			if (isset($_POST['notes'])) {
				$message.="Notes: {$_POST['notes']}\n\n";
			}
			if (isset($_POST['enquiry'])) {
				if (isset($_POST['request_default'])) {	
					if($_POST['enquiry'] == $_POST['request_default']){
						$enquiry = 'N/A';
					} else {
						$enquiry = $_POST['enquiry'];
					}
				} else {
					$enquiry = $_POST['enquiry'];
				}
				$message.="Enquiry: {$enquiry}\n\n";
			}
			if (isset($_POST['message_subject'])) {
				$message.="Subject: {$_POST['message_subject']}\n\n";
			}
			//process a file upload
			if((!empty($_FILES['file'])) && ($_FILES['file']['error'] == 0)) {
			  $filename = basename($_FILES['file']['name']);
			  $ext = substr($filename, strrpos($filename, '.') + 1);
			  if (in_array($_FILES['file']['type'], $global['safe_mime_types']) && $_FILES['file']['size'] < $max_upload_size) {
				  $new_name = time().'.'.$ext;
					$upload_dir = $global['cinema_dir'].$cinema_id.'/uploads/files/'.$new_name;
					$upload_url = $cinema_domain.$cinema_id.'/uploads/files/'.$new_name;
					if (!file_exists($upload_dir)) {
					  if ((move_uploaded_file($_FILES['file']['tmp_name'],$upload_dir))) {
							$message.="Attached file: {$upload_url}\n\n";
					  }
					}
			  }
			}
			//core message
			if($_POST['request'] != ' '){
				$message.="Message: {$_POST['request']}\n";
			}
			//send message
			if (mail($to,$subject,$message,$headers)) {
				header("location: $this_page?ok=Your message was sent, thank you.&$this_qs");
				exit;
			} else {
				header("location: $this_page?er=There was an error sending your message, please contact $to.&$this_qs");
				exit;
			}
		} else {
			$errorMessage = 'Please fill in all the required fields.';
		}
	}
	if (!empty($errorMessage)) {
		$smarty->assign('er', $errorMessage);
	}
}*/

// Include template
$smarty->display($tpl,$cache_id);

?>
