<?php /* Smarty version Smarty-3.1.13, created on 2019-10-02 16:39:12
         compiled from "tpl\coming_soon.tpl" */ ?>
<?php /*%%SmartyHeaderCode:41795d941a9f6223d4-17220641%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '1fab54ab76904edf50d10a76d1fe060a392fd24c' => 
    array (
      0 => 'tpl\\coming_soon.tpl',
      1 => 1569987551,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '41795d941a9f6223d4-17220641',
  'function' => 
  array (
  ),
  'cache_lifetime' => 600,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_5d941a9f696669_97421947',
  'variables' => 
  array (
    'coming_soon' => 0,
    'n' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5d941a9f696669_97421947')) {function content_5d941a9f696669_97421947($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("inc/tpl/header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array(), 0);?>
<div class="information"><h1>Coming Soon</h1><?php  $_smarty_tpl->tpl_vars['n'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['n']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['coming_soon']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['n']->key => $_smarty_tpl->tpl_vars['n']->value){
$_smarty_tpl->tpl_vars['n']->_loop = true;
?><div class="item"><a href="movies/<?php echo $_smarty_tpl->tpl_vars['n']->value['movie_id'];?>
.php"><img src="<?php echo $_smarty_tpl->tpl_vars['n']->value['poster_url'];?>
" alt="<?php echo $_smarty_tpl->tpl_vars['n']->value['title'];?>
 Poster" height="279"></a><h2><a href="movies/<?php echo $_smarty_tpl->tpl_vars['n']->value['movie_id'];?>
.php"><?php echo $_smarty_tpl->tpl_vars['n']->value['title'];?>
</a></h2><span class="details"><?php if ($_smarty_tpl->tpl_vars['n']->value['release_date2']!="TBC"){?><?php echo $_smarty_tpl->tpl_vars['n']->value['release_date'];?>
<?php }else{ ?>Coming Soon<?php }?></span></div><?php } ?></div><?php echo $_smarty_tpl->getSubTemplate ("inc/tpl/footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array(), 0);?>
<?php }} ?>