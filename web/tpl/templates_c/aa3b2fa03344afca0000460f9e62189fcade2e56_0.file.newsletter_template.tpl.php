<?php
/* Smarty version 3.1.33, created on 2019-11-26 21:48:34
  from '/var/www/Cinemanager/web/tpl/newsletter_template.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_5ddce6e2be8726_80841619',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'aa3b2fa03344afca0000460f9e62189fcade2e56' => 
    array (
      0 => '/var/www/Cinemanager/web/tpl/newsletter_template.tpl',
      1 => 1574758111,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:inc/css/email.css' => 1,
  ),
),false)) {
function content_5ddce6e2be8726_80841619 (Smarty_Internal_Template $_smarty_tpl) {
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
} else { ?><!DOCTYPE html><html lang="en-nz"><head><meta http-equiv="Content-Type" content="text/html;charset=UTF-8" /><meta http-equiv="X-UA-Compatible" content="IE=edge"><meta name="viewport" content="width=device-width, initial-scale=1"><style><?php $_smarty_tpl->_subTemplateRender("file:inc/css/email.css", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>ul.sessions {min-height: 0!important;}</style></head><body><table class="full" cellspacing="0" cellpadding="0"><thead><tr class="small-logo"><td><a class="logo hide-med" href="#"><img src="https://shoreline.nz/tpl/inc/img/sl_logo.png" class="sl-logo-sm" height="25"></a></td></tr><tr class="large-logo"><td class="header-container"><img src="https://shoreline.nz/tpl/inc/img/sl_logo.png" class="sl-logo"></td></tr></thead></table><table class="full wrapper" cellspacing="0" cellpadding="0"><tbody><?php if ($_smarty_tpl->tpl_vars['editorial']->value) {?><tr class="featured"><td><?php echo $_smarty_tpl->tpl_vars['editorial']->value;?>
</td></tr><?php }
if ($_smarty_tpl->tpl_vars['ns']->value) {?><tr class="information"><td><h1>Weekly Session Times</h1></td></tr><tr><td><table class="movie-times" cellspacing="0" cellpadding="0"><tbody><?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['ns']->value, 'n', false, NULL, 'n', array (
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['n']->value) {
?><tr class="movie-item"><td class="movie-poster"><a href="https://shoreline.nz/movies/<?php echo $_smarty_tpl->tpl_vars['n']->value['movie_id'];?>
/"><img src="<?php echo $_smarty_tpl->tpl_vars['n']->value['poster_url'];?>
" alt="<?php echo $_smarty_tpl->tpl_vars['n']->value['title'];?>
" width="190" border="0"></a></td><td class="movie-text"><table class="content" cellspacing="0" cellpadding="0"><tr><td style="vertical-align: top;"><h2><a href="https://shoreline.nz/movies/<?php echo $_smarty_tpl->tpl_vars['n']->value['movie_id'];?>
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
</a>														<?php }
} else {
if ((isset($_smarty_tpl->tpl_vars['__smarty_foreach_s']->value['first']) ? $_smarty_tpl->tpl_vars['__smarty_foreach_s']->value['first'] : null)) {
} else {
if ($_smarty_tpl->tpl_vars['cnt']->value == 0) {?><li><strong><?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['date']->value,'%A %e %b');?>
</strong><?php }
}
$_smarty_tpl->_assignInScope('cnt', $_smarty_tpl->tpl_vars['cnt']->value+1);
if (!(isset($_smarty_tpl->tpl_vars['__smarty_foreach_st']->value['first']) ? $_smarty_tpl->tpl_vars['__smarty_foreach_st']->value['first'] : null)) {?>, <?php }?><a href="https://shoreline.nz/bookings/<?php echo $_smarty_tpl->tpl_vars['st']->value['id'];?>
/"><?php echo $_smarty_tpl->tpl_vars['st']->value['time'];?>
</a>													<?php }
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?></li></ul></td></tr></table><hr></td></tr><?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?></tbody></table></td></tr><?php }?></tbody><tfoot><tr><td class="footer"><p style="text-align:center;">To unsubscribe from this email newsletter, <a href="<!--unsub-->" id="unsubscribe">click here</a>.<br>While every attempt is made to ensure this website is accurate,<br>we are not liable for any omissions or errors.<br>Web design and content &copy; <?php echo date('Y');?>
, Shoreline Cinema Waikanae, New Zealand.</p></td></tr></tfoot></table></body></html><?php }
}
}
