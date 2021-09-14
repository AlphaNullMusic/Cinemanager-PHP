<?php
/* Smarty version 3.1.33, created on 2021-09-01 02:07:51
  from '/var/www/Cinemanager/web/tpl/generic.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_612e37b7970288_43503728',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'eaf2bc4d279f500c00286cdb0705b00eb361c29e' => 
    array (
      0 => '/var/www/Cinemanager/web/tpl/generic.tpl',
      1 => 1574633434,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:inc/tpl/header.tpl' => 1,
    'file:inc/tpl/footer.tpl' => 1,
  ),
),false)) {
function content_612e37b7970288_43503728 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_subTemplateRender("file:inc/tpl/header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?><div class="information"><h1><?php echo $_smarty_tpl->tpl_vars['page']->value['title'];?>
</h1><?php echo $_smarty_tpl->tpl_vars['page']->value['content'];?>
</div><?php $_smarty_tpl->_subTemplateRender("file:inc/tpl/footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
}
}
