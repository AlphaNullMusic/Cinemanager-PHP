<?php /* Smarty version Smarty-3.1.13, created on 2019-09-07 15:00:41
         compiled from "I:\Cinemanager\www\Cinemanager\web\tpl\inc\tpl\featured.tpl" */ ?>
<?php /*%%SmartyHeaderCode:156405d731d59b155d1-21740266%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '5fc1f0715030380b6a292a792a35d4316ee9172d' => 
    array (
      0 => 'I:\\Cinemanager\\www\\Cinemanager\\web\\tpl\\inc\\tpl\\featured.tpl',
      1 => 1567468609,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '156405d731d59b155d1-21740266',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'now_showing' => 0,
    'n' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_5d731d59b51547_60821445',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5d731d59b51547_60821445')) {function content_5d731d59b51547_60821445($_smarty_tpl) {?><div class="featured"><div class="content"><div class="featured-carousel"><?php  $_smarty_tpl->tpl_vars['n'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['n']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['now_showing']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['n']->key => $_smarty_tpl->tpl_vars['n']->value){
$_smarty_tpl->tpl_vars['n']->_loop = true;
?><div><div class="content-wrapper poster"><img class="mc-auto show" src="<?php echo $_smarty_tpl->tpl_vars['n']->value['poster'];?>
" width="150" alt="<?php echo $_smarty_tpl->tpl_vars['n']->value['title'];?>
 Poster"></div><div class="content-wrapper text"><h1 class="featured-title"><?php echo $_smarty_tpl->tpl_vars['n']->value['title'];?>
</h1><p><?php echo $_smarty_tpl->tpl_vars['n']->value['synopsis'];?>
</p><a class="btn light" href="movies/<?php echo $_smarty_tpl->tpl_vars['n']->value['movie_id'];?>
.php">See More</a></div></div><?php } ?></div></div></div><?php }} ?>