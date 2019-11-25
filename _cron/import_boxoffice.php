#!/usr/local/bin/php
<?

//import box office results from mpda.org.nz and email on success and failure
//all the functions we need are in moviemanager.biz/admin/boxoffice.php

$global_root_dir = "/home/moviemgr/public_html/";
$_GET['auto'] = true;
require($global_root_dir.'moviemanager.biz/admin/boxoffice.php');

?>