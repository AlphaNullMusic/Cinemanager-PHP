<?php
/* Smarty version 3.1.33, created on 2019-11-26 17:48:40
  from '/var/www/Cinemanager/web/tpl/newsletter_template.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_5ddcaea8db25a6_47094166',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'aa3b2fa03344afca0000460f9e62189fcade2e56' => 
    array (
      0 => '/var/www/Cinemanager/web/tpl/newsletter_template.tpl',
      1 => 1574743712,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:inc/css/styles.css' => 1,
  ),
),false)) {
function content_5ddcaea8db25a6_47094166 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_checkPlugins(array(0=>array('file'=>'/var/www/Cinemanager/_deps/smarty/plugins/modifier.date_format.php','function'=>'smarty_modifier_date_format',),));
if ($_smarty_tpl->tpl_vars['plaintext']->value) {
$_smarty_tpl->_assignInScope('divider', '<br><br>========================================<br><br>');
echo $_smarty_tpl->tpl_vars['plain_editorial']->value;?>
<br><br>For more information on any of our movies please visit www.shorelinecinema.co.nz.<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['ns']->value, 'n', false, NULL, 'n', array (
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['n']->value) {
echo $_smarty_tpl->tpl_vars['divider']->value;
echo $_smarty_tpl->tpl_vars['n']->value['title'];?>
<br><?php echo $_smarty_tpl->tpl_vars['n']->value['classification'];
if ($_smarty_tpl->tpl_vars['n']->value['class_explanation']) {?> (<?php echo $_smarty_tpl->tpl_vars['n']->value['class_explanation'];?>
)<?php }
if ($_smarty_tpl->tpl_vars['n']->value['duration']) {?> - <?php echo $_smarty_tpl->tpl_vars['n']->value['duration'];
}?><br><br><?php if ($_smarty_tpl->tpl_vars['n']->value['synopsis']) {?>Synopsis:<br>&emsp;<?php echo $_smarty_tpl->tpl_vars['n']->value['synopsis'];
}?><br><br><?php if ($_smarty_tpl->tpl_vars['n']->value['comments']) {?><em><?php echo $_smarty_tpl->tpl_vars['n']->value['comments'];?>
</em><br><br><?php }
if ($_smarty_tpl->tpl_vars['n']->value['sessions']) {?>Sessions:<br><?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['n']->value['sessions'], 's', false, 'date', 's', array (
  'first' => true,
  'index' => true,
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['date']->value => $_smarty_tpl->tpl_vars['s']->value) {
$_smarty_tpl->tpl_vars['__smarty_foreach_s']->value['index']++;
$_smarty_tpl->tpl_vars['__smarty_foreach_s']->value['first'] = !$_smarty_tpl->tpl_vars['__smarty_foreach_s']->value['index'];
?>&emsp;<?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['date']->value,'%A %e %b');?>
: <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['s']->value, 'st', false, NULL, 'st', array (
  'first' => true,
  'index' => true,
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['st']->value) {
$_smarty_tpl->tpl_vars['__smarty_foreach_st']->value['index']++;
$_smarty_tpl->tpl_vars['__smarty_foreach_st']->value['first'] = !$_smarty_tpl->tpl_vars['__smarty_foreach_st']->value['index'];
if (!(isset($_smarty_tpl->tpl_vars['__smarty_foreach_st']->value['first']) ? $_smarty_tpl->tpl_vars['__smarty_foreach_st']->value['first'] : null)) {?>, <?php }
echo $_smarty_tpl->tpl_vars['st']->value['time'];
if ($_smarty_tpl->tpl_vars['st']->value['comment']) {?> (<?php echo $_smarty_tpl->tpl_vars['st']->value['comment'];?>
)<?php }
if ($_smarty_tpl->tpl_vars['st']->value['label']) {?> (<?php echo $_smarty_tpl->tpl_vars['st']->value['label'];?>
)<?php }
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?><br><?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);
}?><br>Visit this web page for more information:&nbsp;http://www.shorelinecinema.co.nz/movies/<?php echo $_smarty_tpl->tpl_vars['n']->value['movie_id'];
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);
echo $_smarty_tpl->tpl_vars['divider']->value;
} else { ?><!DOCTYPE html><html lang="en-nz"><head><meta http-equiv="Content-Type" content="text/html;charset=UTF-8" /><meta http-equiv="X-UA-Compatible" content="IE=edge"><meta name="viewport" content="width=device-width, initial-scale=1"><style><?php $_smarty_tpl->_subTemplateRender("file:inc/css/styles.css", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>ul.sessions {min-height: 0!important;}</style></head><body><header><div class="box-auto hide show-med"><img src="https://shoreline.nz/tpl/inc/img/sl_logo.png" class="img-responsive mc-auto show"></div><nav><a class="logo hide-med" href="#"><img src="https://shoreline.nz/tpl/inc/img/sl_logo.png" height="25"></a></nav></header><div class="wrapper"><?php if ($_smarty_tpl->tpl_vars['editorial']->value) {?><div class="featured"><div class="content"><div style="white-space: pre-wrap;"><?php echo $_smarty_tpl->tpl_vars['editorial']->value;?>
</div></div></div><?php }
if ($_smarty_tpl->tpl_vars['ns']->value) {?><div class="information"><h1>Weekly Session Times</h1><ul class="movie-times"><?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['ns']->value, 'n', false, NULL, 'n', array (
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['n']->value) {
?><li><div class="content"><div class="content-wrapper poster"><a href="https://shoreline.nz/movies/<?php echo $_smarty_tpl->tpl_vars['n']->value['movie_id'];?>
/"><img src="<?php echo $_smarty_tpl->tpl_vars['n']->value['poster_url'];?>
" alt="<?php echo $_smarty_tpl->tpl_vars['n']->value['title'];?>
" width="190" border="0"></a></div><div class="content-wrapper text"><h2><a href="https://shoreline.nz/movies/<?php echo $_smarty_tpl->tpl_vars['n']->value['movie_id'];?>
/"><?php echo $_smarty_tpl->tpl_vars['n']->value['title'];?>
</a><span class="details">&nbsp;&nbsp;[<strong><?php echo $_smarty_tpl->tpl_vars['n']->value['classification'];?>
</strong><?php if ($_smarty_tpl->tpl_vars['n']->value['class_explanation']) {?> (<?php echo $_smarty_tpl->tpl_vars['n']->value['class_explanation'];?>
)<?php }
if ($_smarty_tpl->tpl_vars['n']->value['duration']) {?> - <?php echo $_smarty_tpl->tpl_vars['n']->value['duration'];
}?>]</span></h2><strong><em><?php if ($_smarty_tpl->tpl_vars['n']->value['comments']) {?> (<?php echo $_smarty_tpl->tpl_vars['n']->value['comments'];?>
)<?php }?></em></strong><p><?php echo $_smarty_tpl->tpl_vars['n']->value['synopsis'];?>
</p><ul class="sessions"><?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['n']->value['sessions'], 's', false, 'date', 's', array (
  'first' => true,
  'index' => true,
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['date']->value => $_smarty_tpl->tpl_vars['s']->value) {
$_smarty_tpl->tpl_vars['__smarty_foreach_s']->value['index']++;
$_smarty_tpl->tpl_vars['__smarty_foreach_s']->value['first'] = !$_smarty_tpl->tpl_vars['__smarty_foreach_s']->value['index'];
$_smarty_tpl->_assignInScope('cnt', 0);
if ((isset($_smarty_tpl->tpl_vars['__smarty_foreach_s']->value['first']) ? $_smarty_tpl->tpl_vars['__smarty_foreach_s']->value['first'] : null)) {?><li><strong><?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['date']->value,'%A %e %b');?>
</strong><?php }
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['s']->value, 'st', false, NULL, 'st', array (
  'first' => true,
  'index' => true,
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['st']->value) {
$_smarty_tpl->tpl_vars['__smarty_foreach_st']->value['index']++;
$_smarty_tpl->tpl_vars['__smarty_foreach_st']->value['first'] = !$_smarty_tpl->tpl_vars['__smarty_foreach_st']->value['index'];
$_smarty_tpl->_assignInScope('newDate', (($_smarty_tpl->tpl_vars['date']->value).(' ')).($_smarty_tpl->tpl_vars['st']->value['time']));
$_smarty_tpl->_assignInScope('mmDate', (($_smarty_tpl->tpl_vars['date']->value).(' ')).('02:00am'));
if (smarty_modifier_date_format($_smarty_tpl->tpl_vars['newDate']->value,"%Y-%m-%d %H:%M:%S") <= smarty_modifier_date_format($_smarty_tpl->tpl_vars['mmDate']->value,"%Y-%m-%d %H:%M:%S")) {
if ((isset($_smarty_tpl->tpl_vars['__smarty_foreach_s']->value['first']) ? $_smarty_tpl->tpl_vars['__smarty_foreach_s']->value['first'] : null)) {
$_smarty_tpl->_assignInScope('cnt', $_smarty_tpl->tpl_vars['cnt']->value+1);
} else {
if (!(isset($_smarty_tpl->tpl_vars['__smarty_foreach_st']->value['first']) ? $_smarty_tpl->tpl_vars['__smarty_foreach_st']->value['first'] : null)) {?>, <?php }?><a href="https://shoreline.nz/bookings/<?php echo $_smarty_tpl->tpl_vars['st']->value['id'];?>
/"><?php echo $_smarty_tpl->tpl_vars['st']->value['time'];?>
</a>    										<?php }
} else {
if ((isset($_smarty_tpl->tpl_vars['__smarty_foreach_s']->value['first']) ? $_smarty_tpl->tpl_vars['__smarty_foreach_s']->value['first'] : null)) {
} else {
if ($_smarty_tpl->tpl_vars['cnt']->value == 0) {?><li><strong><?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['date']->value,'%A %e %b');?>
</strong><?php }
}
$_smarty_tpl->_assignInScope('cnt', $_smarty_tpl->tpl_vars['cnt']->value+1);
if (!(isset($_smarty_tpl->tpl_vars['__smarty_foreach_st']->value['first']) ? $_smarty_tpl->tpl_vars['__smarty_foreach_st']->value['first'] : null)) {?>, <?php }?><a href="https://shoreline.nz/bookings/<?php echo $_smarty_tpl->tpl_vars['st']->value['id'];?>
/"><?php echo $_smarty_tpl->tpl_vars['st']->value['time'];?>
</a>    									<?php }
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?></li></ul></div></div><hr></li><?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?></ul></div><?php }?><footer><div id="text"><p style="text-align:center;">To unsubscribe from this email newsletter, <a href="<!--unsub-->" id="unsubscribe">click here</a>.<br>While every attempt is made to ensure this website is accurate,<br>we are not liable for any omissions or errors.<br>Web design and content &copy; <?php echo date('Y');?>
, Shoreline Cinema Waikanae, New Zealand.</p></div></footer></div></body></html><?php }
}
}
