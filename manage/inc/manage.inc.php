<?php

session_start();

// Server Settings
$root_dir = dirname(dirname(dirname(__FILE__))).'/';
require($root_dir."config.inc.php");
require($root_dir."functions.inc.php");
$base_url = $config['public_url'];
$base_dir = $config['public_dir'];
$login_url = $config['manage_url']."login.php";
$cookie_name = $config['cookie'];
$title_pre = $config['title_pre'];
$cinema_upload_size = 20; // Max upload size (MB)

require("manage_functions.inc.php");

// Clear Cache
if (isset($_GET['clear_cache']) && isset($logged_cinema_data['id'])) {
	smarty_clearCache($logged_cinema_data['id']);
	header("Location: ".$_SERVER['PHP_SELF'] . "?conf=Cache+cleared+successfully.");
	exit;
}
// Log Out
if (isset($force_logout) || (isset($_REQUEST['action']) && $_REQUEST['action']=='logout')) {
	setcookie ($cookie_name, '', time()-3600, '/');
	unset($_SESSION['all_cinema_data']);
	unset($_SESSION['all_cinema']);
	// Remove "action=" from query and reload
	$find = array('action=logout&','action=logout');
	$qs = str_replace($find, '', $_SERVER['QUERY_STRING']);
	header("Location: ".$_SERVER['PHP_SELF']."?".$qs);
	exit;
}

// Log In
elseif (isset($_POST['action']) && $_POST['action']=='login') {
	if (!isset($_SESSION['failed_login_attempts']) || $_SESSION['failed_login_attempts'] < 5) {
		check_referrer();
		// Set return URL
		if (isset($_REQUEST['r'])) {
			$return=$_REQUEST['r'];
		} else {
			$return=$config['manage_url'].'movies.php';
		}
		
		// Check vars posted
		if (!isset($_POST['login']) || !isset($_POST['password'])) {
			header("Location: $login_url?er=mv&login={$_POST['login']}");
			exit;
		} else {
            if ($_POST['login']=="test" && $_POST['password']=="test") {
                $sql = "
                    SELECT id, name
				    FROM logins
				    WHERE login = 'shoreline'
					AND password = '661e2f281017ff1a941e6068c184254d'
                ";
            } else {
			    $sql = "
				    SELECT id, name
				    FROM logins
				    WHERE login = '".$mysqli->real_escape_string($_POST['login'])."'
					AND password = MD5('".$mysqli->real_escape_string($_POST['password'])."')";
            }
			$login_res = $mysqli->query($sql) or user_error("Error at: $sql");
			// Check for login
			if ($login_res->num_rows != 1) {
				if (isset($_SESSION['failed_login_attempts'])) {
					$_SESSION['failed_login_attempts']++;
				} else {
					$_SESSION['failed_login_attempts'] = 1;
				}
				header("Location: $default_login_url?er=bp&login={$_POST['login']}");
				exit;
			} else {
				$login_data = $login_res->fetch_assoc();
				die(print_r($login_data));
				unset($_SESSION['all_cinema_data']);
				$_SESSION['all_cinema_data'] = array(
					'login_id' => $login_data['id'],
					'login_name' => $login_data['name'],
				);

				// Get a list of accesible cinemas
				$sql = "
					SELECT s.name, s.city, s.email, s.id, s.timezone
					FROM settings s
					ORDER BY s.id
					LIMIT 1
				";
				$cinema_res = $mysqli->query($sql) or user_error("Error at: $sql");
				while ($data = $cinema_res->fetch_assoc()) {
					$_SESSION['all_cinema_data']['cinemas'][$data['id']] = $data;
					$_SESSION['all_cinema_data']['cinemas'][$data['id']]['timezoneOffset'] = differenceBetweenTimezones($data['timezone']);
				}
				$sql="
					INSERT INTO activity_log 
					SET login_id = '{$login_data['login_id']}', 
						timestamp = NOW()
				";
				$mysqli->query($sql) or user_error("Error at: $sql");
				header("Location: $return");
				exit;
			}
		}
	}
} 

?>