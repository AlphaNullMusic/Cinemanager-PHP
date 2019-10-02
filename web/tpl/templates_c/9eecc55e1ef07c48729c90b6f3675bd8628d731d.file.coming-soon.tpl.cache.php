<?php /* Smarty version Smarty-3.1.13, created on 2019-10-02 21:47:49
         compiled from "tpl\coming-soon.tpl" */ ?>
<?php /*%%SmartyHeaderCode:149395d944d5fd33685-69968049%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '9eecc55e1ef07c48729c90b6f3675bd8628d731d' => 
    array (
      0 => 'tpl\\coming-soon.tpl',
      1 => 1570004705,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '149395d944d5fd33685-69968049',
  'function' => 
  array (
  ),
  'cache_lifetime' => 600,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_5d944d5fdc1f85_22419700',
  'variables' => 
  array (
    'coming_soon' => 0,
    'n' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5d944d5fdc1f85_22419700')) {function content_5d944d5fdc1f85_22419700($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("inc/tpl/header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array(), 0);?>
<div class="information"><h1>Coming Soon</h1><?php if ($_smarty_tpl->tpl_vars['coming_soon']->value){?><?php  $_smarty_tpl->tpl_vars['n'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['n']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['coming_soon']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['n']->key => $_smarty_tpl->tpl_vars['n']->value){
$_smarty_tpl->tpl_vars['n']->_loop = true;
?><div class="item"><a href="/movies/<?php echo $_smarty_tpl->tpl_vars['n']->value['movie_id'];?>
/"><img src="<?php echo $_smarty_tpl->tpl_vars['n']->value['poster_url'];?>
" alt="<?php echo $_smarty_tpl->tpl_vars['n']->value['title'];?>
 Poster" height="279"></a><h2><a href="/movies/<?php echo $_smarty_tpl->tpl_vars['n']->value['movie_id'];?>
/"><?php echo $_smarty_tpl->tpl_vars['n']->value['title'];?>
</a></h2><span class="details"><?php if ($_smarty_tpl->tpl_vars['n']->value['release_date2']!="TBC"){?><?php echo $_smarty_tpl->tpl_vars['n']->value['release_date'];?>
<?php }else{ ?>Coming Soon<?php }?></span></div><?php } ?><?php }else{ ?><p>Currently we don't have any movies coming soon.<br />Please <a href="/contact-us/"><strong><em>contact us</em></strong></a> or check back later.</p><?php }?></div><?php echo $_smarty_tpl->getSubTemplate ("inc/tpl/footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array(), 0);?>
<?php }} ?>