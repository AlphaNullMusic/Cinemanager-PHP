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
 * Name:     movie_link<br>
 * Date:     September 22, 2007<br>
 * Purpose:  automate movie url creation<br>
 * Input:<br>
 *         - movie_id = unique movie id
 *         - title = (optional) text to use when naming the link, may be any url friendly string
 *         - section = (optional) link directly to a section of the movie page
 * @author   Andrew Spear
 */

function smarty_function_movie_link($params, &$smarty) {
	global $global;
	$title = (!empty($params['title'])) ? valid_file_name($params['title']) : NULL ;
	$section = (!empty($params['section'])) ? $params['section'] : NULL ;
	$prefix = (!empty($params['external'])) ? $global['public_url'] : NULL ;
	$movie_link = movie_link($params['movie_id'],$title,$section,$prefix);
	if (empty($params['assign'])) {
		return $movie_link;
	} else {
		$smarty->assign($params['assign'], $movie_link);
	}
}

?>
