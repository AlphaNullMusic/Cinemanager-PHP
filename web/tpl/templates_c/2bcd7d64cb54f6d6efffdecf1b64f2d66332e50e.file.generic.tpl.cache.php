<?php /* Smarty version Smarty-3.1.13, created on 2019-10-02 20:31:22
         compiled from "tpl\generic.tpl" */ ?>
<?php /*%%SmartyHeaderCode:173615d94524a3d85a5-92231450%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '2bcd7d64cb54f6d6efffdecf1b64f2d66332e50e' => 
    array (
      0 => 'tpl\\generic.tpl',
      1 => 1570001446,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '173615d94524a3d85a5-92231450',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'page' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_5d94524a40d2a2_18109591',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5d94524a40d2a2_18109591')) {function content_5d94524a40d2a2_18109591($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("inc/tpl/header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array(), 0);?>
<div class="information"><h1><?php echo $_smarty_tpl->tpl_vars['page']->value['title'];?>
</h1><?php echo $_smarty_tpl->tpl_vars['page']->value['content'];?>
</div><?php echo $_smarty_tpl->getSubTemplate ("inc/tpl/footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array(), 0);?>
<?php }} ?>