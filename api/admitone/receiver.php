<?

// Setup

$settings = array(
	'log' => true,
	'logDir' => 'log',
	'timezone' => 'Pacific/Auckland'
);

// Logging

if ($settings['log'] == true) {
	date_default_timezone_set($settings['timezone']);
	$data = print_r($_REQUEST, true);
	//$data = file_get_contents('php://input');
	$file = dirname(__FILE__) . '/' . $settings['logDir'] . '/' . date('Y-m-d H:i:s') . '.txt';
	file_put_contents($file, $data);
}

// Heavy lifting



?>