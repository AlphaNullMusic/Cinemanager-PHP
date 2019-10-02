<?php /* Smarty version Smarty-3.1.13, created on 2019-10-02 21:47:42
         compiled from "tpl\whats-on.tpl" */ ?>
<?php /*%%SmartyHeaderCode:32775d9423ed3de0e7-19377548%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'd813e69cd4ecfd9a04174dd29e8eaf51bff11dc8' => 
    array (
      0 => 'tpl\\whats-on.tpl',
      1 => 1570004797,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '32775d9423ed3de0e7-19377548',
  'function' => 
  array (
  ),
  'cache_lifetime' => 600,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_5d9423ed5767b0_52191705',
  'variables' => 
  array (
    'now_showing' => 0,
    'n' => 0,
    'date' => 0,
    's' => 0,
    'st' => 0,
    'newDate' => 0,
    'mmDate' => 0,
    'cnt' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5d9423ed5767b0_52191705')) {function content_5d9423ed5767b0_52191705($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_date_format')) include 'I:\\Cinemanager\\www\\Cinemanager/_deps/smarty/plugins\\modifier.date_format.php';
?><?php echo $_smarty_tpl->getSubTemplate ("inc/tpl/header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array(), 0);?>
<div class="information"><h1>Session Times</h1><?php if ($_smarty_tpl->tpl_vars['now_showing']->value){?><ul class="movie-times"><?php  $_smarty_tpl->tpl_vars['n'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['n']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['now_showing']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['n']->key => $_smarty_tpl->tpl_vars['n']->value){
$_smarty_tpl->tpl_vars['n']->_loop = true;
?><li><div class="content"><div class="content-wrapper poster"><a href="/movies/<?php echo $_smarty_tpl->tpl_vars['n']->value['movie_id'];?>
/"><img src="<?php echo $_smarty_tpl->tpl_vars['n']->value['poster_url'];?>
" alt="<?php echo $_smarty_tpl->tpl_vars['n']->value['title'];?>
" width="190" border="0"></a></div><div class="content-wrapper text"><h2><a href="/movies/<?php echo $_smarty_tpl->tpl_vars['n']->value['movie_id'];?>
/"><?php echo $_smarty_tpl->tpl_vars['n']->value['title'];?>
</a><span class="details">&nbsp;&nbsp;[<strong><?php echo $_smarty_tpl->tpl_vars['n']->value['classification'];?>
</strong><?php if ($_smarty_tpl->tpl_vars['n']->value['class_explanation']){?> (<?php echo $_smarty_tpl->tpl_vars['n']->value['class_explanation'];?>
)<?php }?><?php if ($_smarty_tpl->tpl_vars['n']->value['duration']){?> - <?php echo $_smarty_tpl->tpl_vars['n']->value['duration'];?>
<?php }?>]</span></h2><strong><em><?php if ($_smarty_tpl->tpl_vars['n']->value['comments']){?> (<?php echo $_smarty_tpl->tpl_vars['n']->value['comments'];?>
)<?php }?></em></strong><ul class="sessions"><?php  $_smarty_tpl->tpl_vars['s'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['s']->_loop = false;
 $_smarty_tpl->tpl_vars['date'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['n']->value['sessions']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['s']->index=-1;
foreach ($_from as $_smarty_tpl->tpl_vars['s']->key => $_smarty_tpl->tpl_vars['s']->value){
$_smarty_tpl->tpl_vars['s']->_loop = true;
 $_smarty_tpl->tpl_vars['date']->value = $_smarty_tpl->tpl_vars['s']->key;
 $_smarty_tpl->tpl_vars['s']->index++;
 $_smarty_tpl->tpl_vars['s']->first = $_smarty_tpl->tpl_vars['s']->index === 0;
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']['s']['first'] = $_smarty_tpl->tpl_vars['s']->first;
?><?php $_smarty_tpl->tpl_vars["cnt"] = new Smarty_variable(0, null, 0);?><?php if ($_smarty_tpl->getVariable('smarty')->value['foreach']['s']['first']){?><li><strong><?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['date']->value,'%A %e %b');?>
</strong><?php }?><?php  $_smarty_tpl->tpl_vars['st'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['st']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['s']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['st']->index=-1;
foreach ($_from as $_smarty_tpl->tpl_vars['st']->key => $_smarty_tpl->tpl_vars['st']->value){
$_smarty_tpl->tpl_vars['st']->_loop = true;
 $_smarty_tpl->tpl_vars['st']->index++;
 $_smarty_tpl->tpl_vars['st']->first = $_smarty_tpl->tpl_vars['st']->index === 0;
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']['st']['first'] = $_smarty_tpl->tpl_vars['st']->first;
?><?php $_smarty_tpl->tpl_vars["newDate"] = new Smarty_variable((($_smarty_tpl->tpl_vars['date']->value).(' ')).($_smarty_tpl->tpl_vars['st']->value['time']), null, 0);?><?php $_smarty_tpl->tpl_vars["mmDate"] = new Smarty_variable((($_smarty_tpl->tpl_vars['date']->value).(' ')).('02:00am'), null, 0);?><?php if (smarty_modifier_date_format($_smarty_tpl->tpl_vars['newDate']->value,"%Y-%m-%d %H:%M:%S")<=smarty_modifier_date_format($_smarty_tpl->tpl_vars['mmDate']->value,"%Y-%m-%d %H:%M:%S")){?><?php if ($_smarty_tpl->getVariable('smarty')->value['foreach']['s']['first']){?><?php $_smarty_tpl->tpl_vars["cnt"] = new Smarty_variable($_smarty_tpl->tpl_vars['cnt']->value+1, null, 0);?><?php }else{ ?><?php if (!$_smarty_tpl->getVariable('smarty')->value['foreach']['st']['first']){?>, <?php }?><a href="/bookings/<?php echo $_smarty_tpl->tpl_vars['st']->value['id'];?>
/"><?php echo $_smarty_tpl->tpl_vars['st']->value['time'];?>
</a><?php if ($_smarty_tpl->tpl_vars['st']->value['label']){?> (<?php echo $_smarty_tpl->tpl_vars['st']->value['label'];?>
)<?php }?><?php }?><?php }else{ ?><?php if ($_smarty_tpl->getVariable('smarty')->value['foreach']['s']['first']){?><?php }else{ ?><?php if ($_smarty_tpl->tpl_vars['cnt']->value==0){?><li><strong><?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['date']->value,'%A %e %b');?>
</strong><?php }?><?php }?><?php $_smarty_tpl->tpl_vars["cnt"] = new Smarty_variable($_smarty_tpl->tpl_vars['cnt']->value+1, null, 0);?><?php if (!$_smarty_tpl->getVariable('smarty')->value['foreach']['st']['first']){?>, <?php }?><a href="/bookings/<?php echo $_smarty_tpl->tpl_vars['st']->value['id'];?>
/"><?php echo $_smarty_tpl->tpl_vars['st']->value['time'];?>
</a><?php if ($_smarty_tpl->tpl_vars['st']->value['label']){?> <i>(<?php echo $_smarty_tpl->tpl_vars['st']->value['label'];?>
)</i><?php }?><?php }?><?php } ?><?php } ?></li></ul></div></div></li><?php } ?></ul><?php }else{ ?><p>Currently we don't have any session times listed.<br />Please <a href="/contact-us/"><strong><em>contact us</em></strong></a> or check back later.</p><?php }?></div><?php echo $_smarty_tpl->getSubTemplate ("inc/tpl/footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array(), 0);?>
<?php }} ?>