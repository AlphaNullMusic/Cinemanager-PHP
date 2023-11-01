<?php
ini_set('display_errors',1);
date_default_timezone_set("Pacific/Auckland");
error_reporting(E_ALL ^ E_NOTICE);
//error_reporting(0);

/////////////////
// Global Vars //
/////////////////

$root_dir = dirname(__FILE__).'/';
$config = array(

	// Basics
	"cinema_name" => "Shoreline Cinema",

	// Manage
	"manage_url" => "https://manage.shoreline.nz/",
	"manage_dir" => $root_dir."manage/",

	// API
	"api_url" => "https://api.shoreline.nz/",
	"api_dir" => $root_dir."api/",

	// Cinema Sites
	"cinema_url" => "https://shorelinecinema.co.nz/",
	"cinema_dir" => $root_dir."web/",
	"template_dir" => $root_dir."web/tpl/",
	
	// Posters
	"poster_url" => "https://posters.shoreline.nz/",
	"poster_dir" => $root_dir."posters/",
	"tmp_poster_dir" => $root_dir."posters/tmp/",

	// Lib Directories
	"libs_dir" => $root_dir."_deps/",
	"smarty_dir" => $root_dir."_deps/smarty/",
	"phpmailer_dir"	=> $root_dir."_deps/phpmailer/",
	
	// Emails
	"no_reply_email" => "noreply@shoreline.nz",
	"reply_email" => "escape@shorelinecinema.co.nz",
	"bounce_email" => "bounces@shorelinecinema.co.nz",
	"support_email"	=> "support@shorelinecinema.co.nz",
	"email_url" => "https://shorelinecinema.co.nz/email-newsletter.php",

	// Booking Email Settings
	"booking_send_email" => "bookings@shoreline.nz",
	"booking_receive_email" => "manager.shoreline.cinema@gmail.com",
	"booking_smtp_server" => "mail.cinemanager.co.nz",
	"booking_email_pass" => "xNbaMYjpWAhN",

	// Error Email Settings
	"admin_email" => "roman@shoreline.nz",
	"error_email" => "errors@shoreline.nz",
	"error_smtp_server" => "mail.cinemanager.co.nz",
	"error_email_pass" => "3mqqZNED7dka",

	// Newsletter Email Settings
	"newsletter_smtp_server" => "mail.cinemanager.co.nz",
	"newsletter_email" => "sessions@shoreline.nz",
	"newsletter_email_pass" => "xNbaMYjpWAhN", 
	
	"cookie" => "Cinemanager",
	"title_pre" => "Cinemanager: ",
	
	// Salts (used by Cinemanager cookies)
	"salt_int" => "6578912",
	"salt_div" => "ne5-8jnm8o9d43d-kc0ds",
	"salt_string" => "l9fg430gj2-f-30d0s048",
	
	// External Booking Systems
	//"bms_session_booking_url" => "http://www.bookmyshow.co.nz/go.aspx",
	//"sentry_session_booking_launchpad_url" => "/sentry.php",
	//"veezi_session_booking_url" => "https://ticketing.us.veezi.com/purchase/",
	
	// Allowed Mime Types
	"safe_mime_types" => array('
	    image/jpeg',
	    'image/png',
	    'application/msword',
	    'application/rtf',
	    'application/pdf',
	    'application/mspublisher',
	    'application/vnd.ms-publisher',
	    'application/vnd.ms-word',
	    'application/vnd.openxmlformats-officedocument.wordprocessingml.document
	'),
	
	// OMDB API Key
	"omdb_api" => "cb84a757",

	// Google Analytics Code
	"ga_code" => "UA-137475424-1"
	
);

//////////////////
// Poster Sizes //
//////////////////

$config['poster_sizes'] = array(
	// Manage Dashboard
	'tiny' => array(
		'name' => 'tiny',
		'width' => 30,
		'height' => 44
	),
	// Featured
	'small' => array(
		'name' => 'small',
		'width' => 150
	),
	// What's On, Movie Page and Bookings
	'medium' => array(
		'name' => 'medium',
		'width' => 190
	),
	// Coming Soon and What's On Today
	'large' => array(
		'name' => 'large',
		'height' => 279
		/*'width' => 100*/
		// keep aspect ratio when converting
	),
	// Original
	'full' => array(
		'name' => 'full',
		/*'height' => 2046
		'width' => 100*/
		// keep aspect ratio when converting
	),
);

//////////////////////////
// Database Connections //
//////////////////////////

define('DB_TYPE', 'mysql');
define('DB_HOST', "localhost");
define('DB_NAME', "shoreline");
define('DB_USER', "shoreline");
define('DB_PASS', "CuM4GZXA4_*bNK=V");

if (isset($use_pdo)) {
	db_pdo();
} else {
	db_direct();
}

function db_pdo() {
	if (!class_exists('db')) {
		try {
			$dbh = new PDO(DB_TYPE.':host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASS);
			$dbh->query('SELECT 1');
			$dbh = null;
		} catch (PDOException $e) {
			print "Error!: " . $e->getMessage() . "<br/>";
			die();
		}
		class db extends PDO {
			function __construct() {
				parent::__construct(DB_TYPE.':host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASS, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
				$this->setAttribute(PDO::ATTR_EMULATE_PREPARES, true);
				$this->setAttribute(PDO::ATTR_PERSISTENT, true);
				$this->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			}
		}
	}
}

function db_direct() {
	global $mysqli;
	$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME) or die("Could not connect to db");
        $mysqli->query("SET NAMES utf8");
}

?>
