<?php
/* Smarty version 3.1.33, created on 2019-12-09 22:57:41
  from '/var/www/Cinemanager/web/tpl/404.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_5dee1a95eb3615_22308083',
  'has_nocache_code' => true,
  'file_dependency' => 
  array (
    'cc9a9c9f7a0917a1a53ea197fc99e7d8293de378' => 
    array (
      0 => '/var/www/Cinemanager/web/tpl/404.tpl',
      1 => 1575885459,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:inc/tpl/footer.tpl' => 1,
  ),
),false)) {
function content_5dee1a95eb3615_22308083 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->compiled->nocache_hash = '19821678305dee1a95e11ed6_34073801';
?>
<!DOCTYPE html>
<html lang="en-nz">
<head>
<meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>404 - Page Not Found</title>
<meta name="description" content="Shoreline Cinema is Waikanae's premiere cinema complex. Find out who we are, what's on, what's coming up, how to contact us and much more.">
<link href="/tpl/inc/css/styles.css" rel="stylesheet" type="text/css" />
<link href="/tpl/inc/css/print.css" rel="stylesheet" type="text/css" media="print" />
</head>
<body>
    <header>  
      <div class="box-auto hide show-med"><img src="/tpl/inc/img/sl_logo.png" class="img-responsive mc-auto show"></div>
      
      <nav>
        <a class="logo hide-med" href="#"><img src="/tpl/inc/img/sl_logo.png" height="25"></a>
        <button class="hide-med" type="button">
            <span class="icon-text">Menu&nbsp;</span>
            <span class="icon"></span>
        </button>
        
        
        <div class="collapse jc-center" id="navbar">
            <ul class="nav-links">
                <li class="nav-item">
                    <a class="nav-link <?php if ($_smarty_tpl->tpl_vars['tpl_name']->value == 'home.tpl') {?>active<?php }?>" href="/home/">HOME</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php if ($_smarty_tpl->tpl_vars['tpl_name']->value == 'whats-on-today.tpl') {?>active<?php }?>" href="/whats-on-today/">WHAT&apos;S ON TODAY</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php if ($_smarty_tpl->tpl_vars['tpl_name']->value == 'whats-on.tpl') {?>active<?php }?>" href="/whats-on/">WHAT&apos;S ON</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php if ($_smarty_tpl->tpl_vars['tpl_name']->value == 'coming-soon.tpl') {?>active<?php }?>" href="/coming-soon/">COMING SOON</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php if ($_smarty_tpl->tpl_vars['tpl_name']->value == 'venue-hire.tpl') {?>active<?php }?>" href="/venue-hire/">VENUE HIRE</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php if ($_smarty_tpl->tpl_vars['tpl_name']->value == 'about-us.tpl') {?>active<?php }?>" href="/about-us/">ABOUT US</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php if ($_smarty_tpl->tpl_vars['tpl_name']->value == 'contact-us.tpl') {?>active<?php }?>" href="/contact-us/">CONTACT US</a>
                </li>
            </ul>
        </div>
      </nav>
    </header>
    
    <div class="wrapper">
			

    
    <div class="information">
        <div class="content">
            <div class="content-wrapper">
                <div class="box">
                    <h2>Sorry... we couldn't find the page you were looking for.</h2>
	                <p><?php echo '/*%%SmartyNocache:19821678305dee1a95e11ed6_34073801%%*/<?php echo \'<?=\';?>/*/%%SmartyNocache:19821678305dee1a95e11ed6_34073801%%*/';?>
($_REQUEST['er'])?'Error: '.$_REQUEST['er']:'';<?php echo '/*%%SmartyNocache:19821678305dee1a95e11ed6_34073801%%*/<?php echo \'?>\';?>/*/%%SmartyNocache:19821678305dee1a95e11ed6_34073801%%*/';?>
</p>
	                <p></p>
	                <p>Click the button below to go back home.</p>
	                <a class="btn dark" href="/home/">Home</a>
                </div>
            </div>    
        </div>
    </div>

    <?php $_smarty_tpl->_subTemplateRender("file:inc/tpl/footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>


<?php }
}
