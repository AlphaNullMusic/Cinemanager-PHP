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
 * Name:     cinema_link<br>
 * Date:     October 29, 2007<br>
 * Purpose:  automate movie url creation<br>
 * Input:<br>
 *         - cinema_id = cinema id							} create link to cinema page
 *         - cinema_name = optional cinema name	}
 *         - region_id = region id							} create link to cinema list
 *         - region_name = optional region name	}
 * @author   Andrew Spear
 */

function smarty_function_cinema_link($params, &$smarty) {
	$db = new db;
	if (empty($params['cinema_id']) && empty($params['region_id'])) {
		trigger_error("cinema_link: missing 'cinema_id' parameter",E_USER_WARNING);
		return;
	}
	//link to cinema page
	if ($params['cinema_id']) {
		$link = '/cinemas/';
		$link .= (!empty($params['cinema_name'])) ? valid_file_name($params['cinema_name']).'-' : '' ;
		$link .= $params['cinema_id'];
		$link .= (!empty($params['section'])) ? '-'.$params['section'] : '' ;
		$link .= '.php';
	//link to cinema list
	} elseif ($params['region_id']) {
		$link = 'cinemas';
		$link .= (!empty($params['region_name'])) ? '-in-'.valid_file_name($params['region_name']) : '' ;
		$link .= '-'.$params['region_id'];
		$link .= '.php';
	}
	if (empty($params['assign'])) {
		return $link;
	} else {
		$smarty->assign($params['assign'], $link);
	}
}

?>
