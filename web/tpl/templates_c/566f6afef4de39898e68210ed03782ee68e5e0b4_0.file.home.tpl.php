<?php
/* Smarty version 3.1.33, created on 2020-02-22 11:00:39
  from '/var/www/Cinemanager/web/tpl/home.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_5e5053076e4af9_34303360',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '566f6afef4de39898e68210ed03782ee68e5e0b4' => 
    array (
      0 => '/var/www/Cinemanager/web/tpl/home.tpl',
      1 => 1575886129,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:inc/tpl/header.tpl' => 1,
    'file:inc/tpl/featured.tpl' => 1,
    'file:inc/tpl/footer.tpl' => 1,
  ),
),false)) {
function content_5e5053076e4af9_34303360 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_subTemplateRender("file:inc/tpl/header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('home'=>true), 0, false);
if ($_smarty_tpl->tpl_vars['now_showing']->value[1]) {
$_smarty_tpl->_subTemplateRender("file:inc/tpl/featured.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
}?><div class="information"><div class="content"><div class="content-wrapper"><div class="box"><?php echo $_smarty_tpl->tpl_vars['page']->value['content'];?>
</div></div><div class="content-wrapper"><div class="box"><?php echo $_smarty_tpl->tpl_vars['name']->value;?>
<h2>Visit Us</h2><iframe title="Shoreline Cinema on the map" src="about:blank" width="100%" height="250" style="border:0" id="home_map" allowfullscreen></iframe></div></div></div></div><?php $_smarty_tpl->_subTemplateRender("file:inc/tpl/footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('home'=>true), 0, false);
}
}
