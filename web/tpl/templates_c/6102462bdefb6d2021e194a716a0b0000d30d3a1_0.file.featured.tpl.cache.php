<?php
/* Smarty version 3.1.33, created on 2019-11-25 16:37:12
  from '/var/www/Cinemanager/web/tpl/inc/tpl/featured.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_5ddb4c680c0dc6_15696660',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '6102462bdefb6d2021e194a716a0b0000d30d3a1' => 
    array (
      0 => '/var/www/Cinemanager/web/tpl/inc/tpl/featured.tpl',
      1 => 1574633434,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5ddb4c680c0dc6_15696660 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->compiled->nocache_hash = '15356403575ddb4c680b1a85_00525843';
?>
<div class="featured"><div class="content"><div class="featured-carousel"><?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['now_showing']->value, 'n', false, NULL, 'n', array (
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['n']->value) {
?><div><div class="content-wrapper poster"><img class="mc-auto show" src="<?php echo $_smarty_tpl->tpl_vars['n']->value['poster_url'];?>
" width="150" alt="<?php echo $_smarty_tpl->tpl_vars['n']->value['title'];?>
 Poster"></div><div class="content-wrapper text"><h1 class="featured-title"><?php echo $_smarty_tpl->tpl_vars['n']->value['title'];?>
</h1><p><?php echo $_smarty_tpl->tpl_vars['n']->value['synopsis'];?>
</p><a class="btn light" href="/movies/<?php echo $_smarty_tpl->tpl_vars['n']->value['movie_id'];?>
/">See More</a></div></div><?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?></div></div></div><?php }
}
