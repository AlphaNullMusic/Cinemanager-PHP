<?php

/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty youtube embed url modifier plugin
 *
 * Type:     modifier<br>
 * Name:     ytembedurl<br>
 * Purpose:  get the youtube embed url from a normal youtube url
 */

function smarty_modifier_ytembedurl($string, $default = '') {
	$query_string = array();
	parse_str(parse_url($string, PHP_URL_QUERY), $query_string);
	//$url = "http://www.youtube.com/v/{$query_string['v']}?autoplay=1&showinfo=0&showsearch=0&rel=0";
	//$url = "http://www.youtube.com/v/{$query_string['v']}?version=3&autoplay=1&showinfo=0&showsearch=0&rel=0";
	//$url = "http://youtube.googleapis.com/v/{$query_string['v']}?autoplay=1&showinfo=0&showsearch=0&rel=0";
	$url = "http://www.youtube.com/embed/{$query_string['v']}?autoplay=1&showinfo=0&showsearch=0&rel=0";
	//$url = "http://www.youtube.com/watch?v={$query_string['v']}&autoplay=1&showinfo=0&showsearch=0&rel=0";
	return $url;
}

?>
