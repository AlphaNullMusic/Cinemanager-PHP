<?php /* Smarty version Smarty-3.1.13, created on 2019-11-24 18:19:54
         compiled from "I:\Cinemanager\www\Cinemanager\web\tpl\home.tpl" */ ?>
<?php /*%%SmartyHeaderCode:73855dda12fa074b38-60023417%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '78497426f836d33251d6bc44e39d37b6d3dcbda5' => 
    array (
      0 => 'I:\\Cinemanager\\www\\Cinemanager\\web\\tpl\\home.tpl',
      1 => 1570002582,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '73855dda12fa074b38-60023417',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'now_showing' => 0,
    'page' => 0,
    'name' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_5dda12fa0c9d05_48997492',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5dda12fa0c9d05_48997492')) {function content_5dda12fa0c9d05_48997492($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("inc/tpl/header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('home'=>true), 0);?>
<?php if ($_smarty_tpl->tpl_vars['now_showing']->value[1]){?><?php echo $_smarty_tpl->getSubTemplate ("inc/tpl/featured.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>
<?php }?><div class="information"><div class="content"><div class="content-wrapper"><div class="box"><?php echo $_smarty_tpl->tpl_vars['page']->value['content'];?>
</div></div><div class="content-wrapper"><div class="box"><?php echo $_smarty_tpl->tpl_vars['name']->value;?>
<h2>Visit Us</h2><iframe title="Shoreline Cinema on the map" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3016.7729275756215!2d175.06186241584493!3d-40.87685717931432!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x6d40a25f6ef06be5%3A0x2d166093f029d9a9!2sShoreline+Cinema!5e0!3m2!1sen!2sus!4v1541431553996" width="100%" height="250" style="border:0" allowfullscreen></iframe></div></div></div></div><?php echo $_smarty_tpl->getSubTemplate ("inc/tpl/footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('home'=>true), 0);?>
<?php }} ?>