<?php /* Smarty version Smarty-3.1.13, created on 2019-11-24 23:45:46
         compiled from "I:\Cinemanager\www\Cinemanager\web\tpl\newsletter_template.tpl" */ ?>
<?php /*%%SmartyHeaderCode:128825dda1f58266b14-88250522%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '33e48017edde97e45a7e359a47e3811a58e26c9e' => 
    array (
      0 => 'I:\\Cinemanager\\www\\Cinemanager\\web\\tpl\\newsletter_template.tpl',
      1 => 1574592337,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '128825dda1f58266b14-88250522',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_5dda1f5838cc63_48497076',
  'variables' => 
  array (
    'plaintext' => 0,
    'plain_editorial' => 0,
    'ns' => 0,
    'divider' => 0,
    'n' => 0,
    'date' => 0,
    's' => 0,
    'st' => 0,
    'editorial' => 0,
    'newDate' => 0,
    'mmDate' => 0,
    'cnt' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5dda1f5838cc63_48497076')) {function content_5dda1f5838cc63_48497076($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_date_format')) include 'I:\\Cinemanager\\www\\Cinemanager/_deps/smarty/plugins\\modifier.date_format.php';
?><?php if ($_smarty_tpl->tpl_vars['plaintext']->value){?><?php $_smarty_tpl->tpl_vars['divider'] = new Smarty_variable('<br><br>========================================<br><br>', null, 0);?><?php echo $_smarty_tpl->tpl_vars['plain_editorial']->value;?>
<br><br>For more information on any of our movies please visit www.shorelinecinema.co.nz.<?php  $_smarty_tpl->tpl_vars['n'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['n']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['ns']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['n']->key => $_smarty_tpl->tpl_vars['n']->value){
$_smarty_tpl->tpl_vars['n']->_loop = true;
?><?php echo $_smarty_tpl->tpl_vars['divider']->value;?>
<?php echo $_smarty_tpl->tpl_vars['n']->value['title'];?>
<br><?php echo $_smarty_tpl->tpl_vars['n']->value['classification'];?>
<?php if ($_smarty_tpl->tpl_vars['n']->value['class_explanation']){?> (<?php echo $_smarty_tpl->tpl_vars['n']->value['class_explanation'];?>
)<?php }?><?php if ($_smarty_tpl->tpl_vars['n']->value['duration']){?> - <?php echo $_smarty_tpl->tpl_vars['n']->value['duration'];?>
<?php }?><br><br><?php if ($_smarty_tpl->tpl_vars['n']->value['synopsis']){?>Synopsis:<br>&emsp;<?php echo $_smarty_tpl->tpl_vars['n']->value['synopsis'];?>
<?php }?><br><br><?php if ($_smarty_tpl->tpl_vars['n']->value['comments']){?><em><?php echo $_smarty_tpl->tpl_vars['n']->value['comments'];?>
</em><br><br><?php }?><?php if ($_smarty_tpl->tpl_vars['n']->value['sessions']){?>Sessions:<br><?php  $_smarty_tpl->tpl_vars['s'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['s']->_loop = false;
 $_smarty_tpl->tpl_vars['date'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['n']->value['sessions']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['s']->index=-1;
foreach ($_from as $_smarty_tpl->tpl_vars['s']->key => $_smarty_tpl->tpl_vars['s']->value){
$_smarty_tpl->tpl_vars['s']->_loop = true;
 $_smarty_tpl->tpl_vars['date']->value = $_smarty_tpl->tpl_vars['s']->key;
 $_smarty_tpl->tpl_vars['s']->index++;
 $_smarty_tpl->tpl_vars['s']->first = $_smarty_tpl->tpl_vars['s']->index === 0;
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']['s']['first'] = $_smarty_tpl->tpl_vars['s']->first;
?>&emsp;<?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['date']->value,'%A %e %b');?>
: <?php  $_smarty_tpl->tpl_vars['st'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['st']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['s']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['st']->index=-1;
foreach ($_from as $_smarty_tpl->tpl_vars['st']->key => $_smarty_tpl->tpl_vars['st']->value){
$_smarty_tpl->tpl_vars['st']->_loop = true;
 $_smarty_tpl->tpl_vars['st']->index++;
 $_smarty_tpl->tpl_vars['st']->first = $_smarty_tpl->tpl_vars['st']->index === 0;
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']['st']['first'] = $_smarty_tpl->tpl_vars['st']->first;
?><?php if (!$_smarty_tpl->getVariable('smarty')->value['foreach']['st']['first']){?>, <?php }?><?php echo $_smarty_tpl->tpl_vars['st']->value['time'];?>
<?php if ($_smarty_tpl->tpl_vars['st']->value['comment']){?> (<?php echo $_smarty_tpl->tpl_vars['st']->value['comment'];?>
)<?php }?><?php if ($_smarty_tpl->tpl_vars['st']->value['label']){?> (<?php echo $_smarty_tpl->tpl_vars['st']->value['label'];?>
)<?php }?><?php } ?><br><?php } ?><?php }?><br>Visit this web page for more information:&nbsp;http://www.shorelinecinema.co.nz/movies/<?php echo $_smarty_tpl->tpl_vars['n']->value['movie_id'];?>
<?php } ?><?php echo $_smarty_tpl->tpl_vars['divider']->value;?>
<?php }else{ ?><!DOCTYPE html><html lang="en-nz"><head><meta http-equiv="Content-Type" content="text/html;charset=UTF-8" /><meta http-equiv="X-UA-Compatible" content="IE=edge"><meta name="viewport" content="width=device-width, initial-scale=1"><style><?php echo $_smarty_tpl->getSubTemplate ("inc/css/styles.css", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>
ul.sessions {min-height: 0!important;}</style></head><body><header><div class="box-auto hide show-med"><img src="https://shoreline.nz/tpl/inc/img/sl_logo.png" class="img-responsive mc-auto show"></div><nav><a class="logo hide-med" href="#"><img src="https://shoreline.nz/tpl/inc/img/sl_logo.png" height="25"></a></nav></header><div class="wrapper"><?php if ($_smarty_tpl->tpl_vars['editorial']->value){?><div class="featured"><div class="content"><div style="white-space: pre-wrap;"><?php echo $_smarty_tpl->tpl_vars['editorial']->value;?>
</div></div></div><?php }?><?php if ($_smarty_tpl->tpl_vars['ns']->value){?><div class="information"><h1>Weekly Session Times</h1><ul class="movie-times"><?php  $_smarty_tpl->tpl_vars['n'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['n']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['ns']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['n']->key => $_smarty_tpl->tpl_vars['n']->value){
$_smarty_tpl->tpl_vars['n']->_loop = true;
?><li><div class="content"><div class="content-wrapper poster"><a href="https://shoreline.nz/movies/<?php echo $_smarty_tpl->tpl_vars['n']->value['movie_id'];?>
/"><img src="<?php echo $_smarty_tpl->tpl_vars['n']->value['poster_url'];?>
" alt="<?php echo $_smarty_tpl->tpl_vars['n']->value['title'];?>
" width="190" border="0"></a></div><div class="content-wrapper text"><h2><a href="https://shoreline.nz/movies/<?php echo $_smarty_tpl->tpl_vars['n']->value['movie_id'];?>
/"><?php echo $_smarty_tpl->tpl_vars['n']->value['title'];?>
</a><span class="details">&nbsp;&nbsp;[<strong><?php echo $_smarty_tpl->tpl_vars['n']->value['classification'];?>
</strong><?php if ($_smarty_tpl->tpl_vars['n']->value['class_explanation']){?> (<?php echo $_smarty_tpl->tpl_vars['n']->value['class_explanation'];?>
)<?php }?><?php if ($_smarty_tpl->tpl_vars['n']->value['duration']){?> - <?php echo $_smarty_tpl->tpl_vars['n']->value['duration'];?>
<?php }?>]</span></h2><strong><em><?php if ($_smarty_tpl->tpl_vars['n']->value['comments']){?> (<?php echo $_smarty_tpl->tpl_vars['n']->value['comments'];?>
)<?php }?></em></strong><p><?php echo $_smarty_tpl->tpl_vars['n']->value['synopsis'];?>
</p><ul class="sessions"><?php  $_smarty_tpl->tpl_vars['s'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['s']->_loop = false;
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
?><?php $_smarty_tpl->tpl_vars["newDate"] = new Smarty_variable((($_smarty_tpl->tpl_vars['date']->value).(' ')).($_smarty_tpl->tpl_vars['st']->value['time']), null, 0);?><?php $_smarty_tpl->tpl_vars["mmDate"] = new Smarty_variable((($_smarty_tpl->tpl_vars['date']->value).(' ')).('02:00am'), null, 0);?><?php if (smarty_modifier_date_format($_smarty_tpl->tpl_vars['newDate']->value,"%Y-%m-%d %H:%M:%S")<=smarty_modifier_date_format($_smarty_tpl->tpl_vars['mmDate']->value,"%Y-%m-%d %H:%M:%S")){?><?php if ($_smarty_tpl->getVariable('smarty')->value['foreach']['s']['first']){?><?php $_smarty_tpl->tpl_vars["cnt"] = new Smarty_variable($_smarty_tpl->tpl_vars['cnt']->value+1, null, 0);?><?php }else{ ?><?php if (!$_smarty_tpl->getVariable('smarty')->value['foreach']['st']['first']){?>, <?php }?><a href="https://shoreline.nz/bookings/<?php echo $_smarty_tpl->tpl_vars['st']->value['id'];?>
/"><?php echo $_smarty_tpl->tpl_vars['st']->value['time'];?>
</a><?php }?><?php }else{ ?><?php if ($_smarty_tpl->getVariable('smarty')->value['foreach']['s']['first']){?><?php }else{ ?><?php if ($_smarty_tpl->tpl_vars['cnt']->value==0){?><li><strong><?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['date']->value,'%A %e %b');?>
</strong><?php }?><?php }?><?php $_smarty_tpl->tpl_vars["cnt"] = new Smarty_variable($_smarty_tpl->tpl_vars['cnt']->value+1, null, 0);?><?php if (!$_smarty_tpl->getVariable('smarty')->value['foreach']['st']['first']){?>, <?php }?><a href="https://shoreline.nz/bookings/<?php echo $_smarty_tpl->tpl_vars['st']->value['id'];?>
/"><?php echo $_smarty_tpl->tpl_vars['st']->value['time'];?>
</a><?php }?><?php } ?><?php } ?></li></ul></div></div><hr></li><?php } ?></ul></div><?php }?><footer><div id="text"><p style="text-align:center;">To unsubscribe from this email newsletter, <a href="<!--unsub-->" id="unsubscribe">click here</a>.<br>While every attempt is made to ensure this website is accurate,<br>we are not liable for any omissions or errors.<br>Web design and content &copy; <?php echo date('Y');?>
, Shoreline Cinema Waikanae, New Zealand.</p></div></footer></div></body></html><?php }?><?php }} ?>