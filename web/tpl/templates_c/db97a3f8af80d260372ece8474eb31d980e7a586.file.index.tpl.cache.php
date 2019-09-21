<?php /* Smarty version Smarty-3.1.13, created on 2019-09-08 17:21:12
         compiled from "tpl\index.tpl" */ ?>
<?php /*%%SmartyHeaderCode:231785d731d34a3cba4-66403067%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'db97a3f8af80d260372ece8474eb31d980e7a586' => 
    array (
      0 => 'tpl\\index.tpl',
      1 => 1567825196,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '231785d731d34a3cba4-66403067',
  'function' => 
  array (
  ),
  'cache_lifetime' => 600,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_5d731d34a7b4b2_82222762',
  'variables' => 
  array (
    'page' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5d731d34a7b4b2_82222762')) {function content_5d731d34a7b4b2_82222762($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("inc/tpl/header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('home'=>true), 0);?>
<?php echo $_smarty_tpl->getSubTemplate ("inc/tpl/featured.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array(), 0);?>
<div class="information"><div class="content"><div class="content-wrapper"><div class="box"><?php echo $_smarty_tpl->tpl_vars['page']->value['content'];?>
</div></div><div class="content-wrapper"><div class="box"><h2>Visit Us</h2><iframe title="Shoreline Cinema on the map" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3016.7729275756215!2d175.06186241584493!3d-40.87685717931432!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x6d40a25f6ef06be5%3A0x2d166093f029d9a9!2sShoreline+Cinema!5e0!3m2!1sen!2sus!4v1541431553996" width="100%" height="250" style="border:0" allowfullscreen></iframe></div></div></div></div><?php echo $_smarty_tpl->getSubTemplate ("inc/tpl/footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('home'=>true), 0);?>
<?php }} ?>