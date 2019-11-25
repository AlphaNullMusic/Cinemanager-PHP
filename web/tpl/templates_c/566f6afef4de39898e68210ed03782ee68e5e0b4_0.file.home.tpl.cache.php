<?php
/* Smarty version 3.1.33, created on 2019-11-25 16:37:12
  from '/var/www/Cinemanager/web/tpl/home.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_5ddb4c6809efa0_33190471',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '566f6afef4de39898e68210ed03782ee68e5e0b4' => 
    array (
      0 => '/var/www/Cinemanager/web/tpl/home.tpl',
      1 => 1574633434,
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
function content_5ddb4c6809efa0_33190471 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->compiled->nocache_hash = '15909497585ddb4c68096fd6_51530971';
$_smarty_tpl->_subTemplateRender("file:inc/tpl/header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, $_smarty_tpl->cache_lifetime, array('home'=>true), 0, false);
if ($_smarty_tpl->tpl_vars['now_showing']->value[1]) {
$_smarty_tpl->_subTemplateRender("file:inc/tpl/featured.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, $_smarty_tpl->cache_lifetime, array(), 0, false);
}?><div class="information"><div class="content"><div class="content-wrapper"><div class="box"><?php echo $_smarty_tpl->tpl_vars['page']->value['content'];?>
</div></div><div class="content-wrapper"><div class="box"><?php echo $_smarty_tpl->tpl_vars['name']->value;?>
<h2>Visit Us</h2><iframe title="Shoreline Cinema on the map" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3016.7729275756215!2d175.06186241584493!3d-40.87685717931432!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x6d40a25f6ef06be5%3A0x2d166093f029d9a9!2sShoreline+Cinema!5e0!3m2!1sen!2sus!4v1541431553996" width="100%" height="250" style="border:0" allowfullscreen></iframe></div></div></div></div><?php $_smarty_tpl->_subTemplateRender("file:inc/tpl/footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, $_smarty_tpl->cache_lifetime, array('home'=>true), 0, false);
}
}
