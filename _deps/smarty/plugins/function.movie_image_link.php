<?php

/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */

/**
 * Smarty {movie_image_link} function plugin
 *
 * Type:     function<br>
 * Name:     movie_image_link<br>
 * Date:     September 22, 2007<br>
 * Purpose:  automate movie image url creation<br>
 * Input:<br>
 *         - name = image name
 *         - size = final image size (available sizes specified in /_libs/images.inc.php)
 * @author   Andrew Spear
 */

function smarty_function_movie_image_link($params, &$smarty) {
	global $global;
	$db = new db;
	$link = $global['movie_image_url'];
	if (empty($params['name'])) {
		trigger_error("movie_image_link: missing 'name' parameter",E_USER_WARNING);
		return;
	}
	if (empty($params['size'])) {
		trigger_error("movie_image_link: missing 'size' parameter",E_USER_WARNING);
		return;
	}
	$link .= valid_file_name($params['name']).'_'.$params['size'].'.jpg';
  if (isset($params['assign'])) {
	  $smarty->assign($params['assign'], $link);
  } else {
		return $link;
  }
}

?>
