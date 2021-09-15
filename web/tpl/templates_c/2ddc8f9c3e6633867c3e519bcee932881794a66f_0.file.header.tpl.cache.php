<?php
/* Smarty version 3.1.33, created on 2021-09-16 11:25:12
  from '/var/www/Cinemanager/web/tpl/inc/tpl/header.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_614280d8a22567_61124347',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '2ddc8f9c3e6633867c3e519bcee932881794a66f' => 
    array (
      0 => '/var/www/Cinemanager/web/tpl/inc/tpl/header.tpl',
      1 => 1631748311,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_614280d8a22567_61124347 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->compiled->nocache_hash = '656053902614280d8a16356_88895655';
?>
<!DOCTYPE html><html lang="en-nz"><head><meta http-equiv="Content-Type" content="text/html;charset=UTF-8" /><meta http-equiv="X-UA-Compatible" content="IE=edge"><meta name="viewport" content="width=device-width, initial-scale=1"><title><?php if ($_smarty_tpl->tpl_vars['tpl_name']->value == 'home.tpl') {?>Shoreline Cinema Waikanae<?php }
if ($_smarty_tpl->tpl_vars['tpl_name']->value == 'whats-on-today.tpl') {?>What's On Today<?php }
if ($_smarty_tpl->tpl_vars['tpl_name']->value == 'whats-on.tpl') {?>What's On<?php }
if ($_smarty_tpl->tpl_vars['tpl_name']->value == 'coming-soon.tpl') {?>Coming Soon<?php }
if ($_smarty_tpl->tpl_vars['tpl_name']->value == 'venue-hire.tpl') {?>Venue Hire<?php }
if ($_smarty_tpl->tpl_vars['tpl_name']->value == 'about-us.tpl') {?>About Us<?php }
if ($_smarty_tpl->tpl_vars['tpl_name']->value == 'contact-us.tpl') {?>Contact Us<?php }?></title><meta name="description" content="Shoreline Cinema is Waikanae's premiere cinema complex. Find out who we are, what's on, what's coming up, how to contact us and much more."><link href="/tpl/inc/css/styles.css" rel="stylesheet" type="text/css" /><link href="/tpl/inc/css/print.css" rel="stylesheet" type="text/css" media="print" /><?php if ($_smarty_tpl->tpl_vars['js_checkform']->value) {
echo '<script'; ?>
 language="JavaScript" src="../includes/checkform.js"><?php echo '</script'; ?>
><?php }
if ($_smarty_tpl->tpl_vars['home']->value) {?><link rel="stylesheet" type="text/css" href="/tpl/inc/css/slick.css"/><link rel="stylesheet" type="text/css" href="/tpl/inc/css/slick-theme.css"/><?php }?></head><body><header><div class="box-auto hide show-med"><img src="/tpl/inc/img/sl_logo.png" class="img-responsive mc-auto show"></div><nav><a class="logo hide-med" href="#"><img src="/tpl/inc/img/sl_logo.png" height="25"></a><button class="hide-med" type="button"><span class="icon-text">Menu&nbsp;</span><span class="icon"></span></button><div class="collapse jc-center" id="navbar"><ul class="nav-links"><li class="nav-item"><a class="nav-link <?php if ($_smarty_tpl->tpl_vars['tpl_name']->value == 'home.tpl') {?>active<?php }?>" href="/home/">HOME</a></li><li class="nav-item"><a class="nav-link <?php if ($_smarty_tpl->tpl_vars['tpl_name']->value == 'whats-on-today.tpl') {?>active<?php }?>" href="/whats-on-today/">WHAT&apos;S ON TODAY</a></li><li class="nav-item"><a class="nav-link <?php if ($_smarty_tpl->tpl_vars['tpl_name']->value == 'whats-on.tpl') {?>active<?php }?>" href="/whats-on/">WHAT&apos;S ON</a></li><li class="nav-item"><a class="nav-link <?php if ($_smarty_tpl->tpl_vars['tpl_name']->value == 'coming-soon.tpl') {?>active<?php }?>" href="/coming-soon/">COMING SOON</a></li><?php if ($_smarty_tpl->tpl_vars['page_list']->value) {
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['page_list']->value, 'page');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['page']->value) {
?><li class="nav-item"><a class="nav-link <?php if ($_smarty_tpl->tpl_vars['current_page']->value == $_smarty_tpl->tpl_vars['page']->value['reference']) {?>active<?php }?>" href="/<?php echo $_smarty_tpl->tpl_vars['page']->value['reference'];?>
"><?php ob_start();
echo strtoupper($_smarty_tpl->tpl_vars['page']->value['title']);
$_prefixVariable1 = ob_get_clean();
echo $_prefixVariable1;?>
</a></li><?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);
}?></ul></div></nav></header><div class="wrapper">
<?php }
}
