<?php
ini_set('display_errors',1); 
error_reporting(E_ALL ^ E_NOTICE);

/////////////////
// Global Vars //
/////////////////

$root_dir = dirname(__FILE__).'/';
$config = array(

	// Cineguide
	"public_url"		=> "https://www.cineguide.ga/",
	"public_url_secure"	=> "https://secure.cinemanager.ga/",
	"public_dir"		=> $root_dir."cineguide.ga.2019/",

	// Cinemanager
	"manage_url" => "https://manage.shoreline.ml/",
	"manage_dir" => $root_dir."manage/",

	// API
	"api_url" => "https://api.cinemanager.ga/",
	"api_dir" => $root_dir."api.cinemanager.ga/",

	// Cinema Sites
	"cinema_url" => "https://cinemas.cinemanager.ga/",
	"cinema_dir" => $root_dir."cinemas.cinemanager.ga/",

	// Movie Images
	"movie_image_url" => "https://media.moviemanager.biz/movies/",
	"movie_image_url_secure" => "https://media.moviemanager.biz/movies/",
	"movie_image_dir" => $root_dir."media.moviemanager.biz/movie_images/",
	"image_overlay_dir"	=> $root_dir."media.moviemanager.biz/overlays/",

	// Movie Images
	"movie_trailer_url"	=> "http://media2.cinemanager.ga/trailers/",
	"movie_trailer_dir"	=> $root_dir."media.cinemanager.ga/movie_trailers/",

	// Cinema Images
	"cinema_image_url" => "http://media.cinemanager.ga/cinemas/",
	"cinema_image_dir" => $root_dir."media.cinemanager.ga/cinema_images/",

	// Cinema News Files
	"cinema_news_url" => "http://media.cinemanager.ga/cinema_news/",
	"cinema_news_dir" => $root_dir."media.cinemanager.ga/cinema_news/",

	// External Data Directory
	"external_dir" => $root_dir."_external/",	

	// Lib Directories
	"libs_dir" => $root_dir."_deps/",
	"smarty_dir" => $root_dir."_deps/smarty/",
	"phpmailer_dir"	=> $root_dir."_deps/phpmailer/",
	
	// Emails
	"no_reply_email" => "noreply@cinemanager.ga",
	"bounce_email" => "bounces@cinemanager.ga",
	"support_email"	=> "support@cinemanager.ga",
	"sessions_email" => "sessions@cinemanager.ga",
	
	"cookie" => "Cinemanager",
	"title_pre" => "Cinemanager: ",
	
	// Salts (used by Cinemanager cookies)
	"salt_int" => "6578912",
	"salt_div" => "ne5-8jnm8o9d43d-kc0ds",
	"salt_string" => "l9fg430gj2-f-30d0s048",
	
	// External Booking Systems
	"bms_session_booking_url" => "http://www.bookmyshow.co.nz/go.aspx",
	"sentry_session_booking_launchpad_url" => "/sentry.php",
	"veezi_session_booking_url" => "https://ticketing.us.veezi.com/purchase/",
	
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
	"omdb_api" => "cb84a757"
	
);

/////////////////
// Image Sizes //
/////////////////

$config['image_sizes'] = array(
	// Raw Image
	'raw' => array(
		'width' => 3000,
		'height' => 3000,
		'output' => 'jpg:85'
	),
	// Old Still Sizes
	'small' => array(
		'width' => 100,
		'height' => 100
	),
	'normal' => array(
		'width' => 180,
		'height' => 180,
	),
	'medium' => array(
		'width' => 250,
		'height' => 250,
	),
	// Old Poster Sizes
	'tiny' => array(
		'width' => 80,
		'height' => 150,
	),
	'poster' => array(
		'width' => 150,
		'height' => 300,
	),
	'large' => array(
		'width' => 250,
		'height' => 500,
	),
	// Mobile Sizes
	'mobilemini' => array(
		'width' => 34,
		'height' => 46,
		'crop_style' => 'narrow_centre',
	),
	'mobileposter' => array(
		'width' => 86,
		'height' => 126,
		'crop_style' => 'narrow_centre',
	),
	'mobilepostermini' => array(
		'width' => 40,
		'height' => 80,
	),
	// Mobile App Sizes
	'appposter' => array(
		'width' => 640,
		'height' => 1136,
	),
	// Non-Poster Sizes
	'aithumb' => array(
		'width' => 50,
		'height' => 50,
		'crop_style' => 'narrow_centre'
	),
	'aimedium' => array(
		'width' => 400,
		'height' => 600,
	),
);

//////////////////////////
// Database Connections //
//////////////////////////

define('DB_TYPE', 'mysql');
define('DB_HOST', "mysql1003.mochahost.com");
define('DB_NAME', "applemac_shoreline");
define('DB_USER', "applemac_cinemgr");
define('DB_PASS', "qCd2Gopp#WeX");

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