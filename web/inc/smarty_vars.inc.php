<?php

// Common smarty variables
if (!defined('SMARTY_DIR')) {
	define('SMARTY_DIR', $config['smarty_dir']);
}
require_once(SMARTY_DIR.'Smarty.class.php');
$smarty = new Smarty;
$smarty->template_dir	= $config['cinema_dir'].'/tpl/';
$smarty->compile_dir	= $config['cinema_dir'].'tpl/templates_c/';
$smarty->config_dir		= $config['cinema_dir'].'tpl/configs/';
$smarty->cache_dir		= $config['cinema_dir'].'tpl/cache/';
$smarty->caching 		= 1; // 0=off 1=ok
$smarty->compile_check	= true; // True checks for template and config changes and re-caches if necessary
$smarty->cache_lifetime	= 60*10; // In seconds

// Disable caching when error or confirmation messages are sent
if (isset($uncache_flags)) {
	foreach ($uncache_flags as $u) {
		if (isset($_REQUEST[$u])) {
			$smarty->caching = 0;
		}
	}
}

if (isset($cinema_data)) {
	$smarty->assign('cinema_data',$cinema_data);
}

?>