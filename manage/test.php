<?php

require('inc/manage.inc.php');
$movie_list = get_movie_list_full('cs', 'tbc,m.release_date,m.title', 7, '%e %b', '%e %b', 500, 'today', NULL, NULL, false, 'tiny');

print_r($movie_list);

?>
