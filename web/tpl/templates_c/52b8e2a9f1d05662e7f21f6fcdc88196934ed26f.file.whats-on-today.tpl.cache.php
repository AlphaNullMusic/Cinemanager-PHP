<?php /* Smarty version Smarty-3.1.13, created on 2019-10-02 21:47:38
         compiled from "tpl\whats-on-today.tpl" */ ?>
<?php /*%%SmartyHeaderCode:103875d94295b818f60-34883451%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '52b8e2a9f1d05662e7f21f6fcdc88196934ed26f' => 
    array (
      0 => 'tpl\\whats-on-today.tpl',
      1 => 1570004818,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '103875d94295b818f60-34883451',
  'function' => 
  array (
  ),
  'cache_lifetime' => 600,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_5d94295b9d0ff4_48385585',
  'variables' => 
  array (
    'sessions' => 0,
    's' => 0,
    'st' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5d94295b9d0ff4_48385585')) {function content_5d94295b9d0ff4_48385585($_smarty_tpl) {?><?php if (!is_callable('smarty_function_cycle')) include 'I:\\Cinemanager\\www\\Cinemanager/_deps/smarty/plugins\\function.cycle.php';
?><?php echo $_smarty_tpl->getSubTemplate ("inc/tpl/header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array(), 0);?>
<div class="information"><h1>What's on Today</h1><?php if ($_smarty_tpl->tpl_vars['sessions']->value){?><?php  $_smarty_tpl->tpl_vars['s'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['s']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['sessions']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['s']->key => $_smarty_tpl->tpl_vars['s']->value){
$_smarty_tpl->tpl_vars['s']->_loop = true;
?><div class="featured-poster item"><div style="clear:<?php echo smarty_function_cycle(array('values'=>"left,none"),$_smarty_tpl);?>
;"><a href="/movies/<?php echo $_smarty_tpl->tpl_vars['s']->value['movie_id'];?>
/"><img src="<?php echo $_smarty_tpl->tpl_vars['s']->value['poster_url'];?>
" alt="<?php echo $_smarty_tpl->tpl_vars['s']->value['title'];?>
" height="279" border="0"></a><h2><a href="/movies/<?php echo $_smarty_tpl->tpl_vars['s']->value['movie_id'];?>
/"><?php echo $_smarty_tpl->tpl_vars['s']->value['title'];?>
</a> <span class="details">(<?php echo $_smarty_tpl->tpl_vars['s']->value['classification'];?>
)</span></h2><p><strong><?php  $_smarty_tpl->tpl_vars['st'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['st']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['s']->value['sessions']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['st']->index=-1;
foreach ($_from as $_smarty_tpl->tpl_vars['st']->key => $_smarty_tpl->tpl_vars['st']->value){
$_smarty_tpl->tpl_vars['st']->_loop = true;
 $_smarty_tpl->tpl_vars['st']->index++;
 $_smarty_tpl->tpl_vars['st']->first = $_smarty_tpl->tpl_vars['st']->index === 0;
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']['st']['first'] = $_smarty_tpl->tpl_vars['st']->first;
?><?php if (!$_smarty_tpl->getVariable('smarty')->value['foreach']['st']['first']){?>, <?php }?><?php echo $_smarty_tpl->tpl_vars['st']->value['time'];?>
<?php if ($_smarty_tpl->tpl_vars['st']->value['comment']){?> (<?php echo $_smarty_tpl->tpl_vars['st']->value['comment'];?>
)<?php }?><?php if ($_smarty_tpl->tpl_vars['st']->value['label']){?> (<?php echo $_smarty_tpl->tpl_vars['st']->value['label'];?>
)<?php }?><?php } ?></strong></p><a class="btn dark" href="/movies/<?php echo $_smarty_tpl->tpl_vars['s']->value['movie_id'];?>
/">More details</a></div></div><?php } ?><?php }else{ ?><p>Currently we don't have any session times listed.<br />Please <a href="/contact-us/"><strong><em>contact us</em></strong></a> or check back later.</p><?php }?></div><?php echo $_smarty_tpl->getSubTemplate ("inc/tpl/footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array(), 0);?>
<?php }} ?>