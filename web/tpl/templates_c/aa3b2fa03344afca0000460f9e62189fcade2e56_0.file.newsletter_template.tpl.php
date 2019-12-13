<?php
/* Smarty version 3.1.33, created on 2019-12-13 19:59:22
  from '/var/www/Cinemanager/web/tpl/newsletter_template.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_5df336cad75952_86146187',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'aa3b2fa03344afca0000460f9e62189fcade2e56' => 
    array (
      0 => '/var/www/Cinemanager/web/tpl/newsletter_template.tpl',
      1 => 1576220358,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:inc/css/email.css' => 1,
  ),
),false)) {
function content_5df336cad75952_86146187 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_checkPlugins(array(0=>array('file'=>'/var/www/Cinemanager/_deps/smarty/plugins/modifier.date_format.php','function'=>'smarty_modifier_date_format',),));
if ($_smarty_tpl->tpl_vars['plaintext']->value) {?>Shoreline Cinema Weekly Newsletter<?php echo "\n\n";
$_smarty_tpl->_assignInScope('divider', '========================================');
if ($_smarty_tpl->tpl_vars['plain_editorial']->value) {
echo $_smarty_tpl->tpl_vars['plain_editorial']->value;
}
echo "\n\n";?>
For more information on any of our movies please visit www.shorelinecinema.co.nz.<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['ns']->value, 'n', false, NULL, 'n', array (
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['n']->value) {
echo "\n\n";
echo $_smarty_tpl->tpl_vars['divider']->value;
echo "\n\n";
echo $_smarty_tpl->tpl_vars['n']->value['title'];
echo "\n";
echo $_smarty_tpl->tpl_vars['n']->value['classification'];
if ($_smarty_tpl->tpl_vars['n']->value['class_explanation']) {?> (<?php echo $_smarty_tpl->tpl_vars['n']->value['class_explanation'];?>
)<?php }
if ($_smarty_tpl->tpl_vars['n']->value['duration']) {?> - <?php echo $_smarty_tpl->tpl_vars['n']->value['duration'];
}
echo "\n\n";
if ($_smarty_tpl->tpl_vars['n']->value['synopsis']) {?>Synopsis:<?php echo "\n";
echo preg_replace('!^!m',str_repeat(' ',10),$_smarty_tpl->tpl_vars['n']->value['synopsis']);
}
echo "\n";
echo "\n";
if ($_smarty_tpl->tpl_vars['n']->value['comments']) {?>Comments:<?php echo "\n";?>
<em><?php echo preg_replace('!^!m',str_repeat(' ',10),$_smarty_tpl->tpl_vars['n']->value['comments']);?>
</em><?php echo "\n\n";
}
if ($_smarty_tpl->tpl_vars['n']->value['sessions']) {?>Sessions:<?php echo "\n";
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['n']->value['sessions'], 's', false, 'date', 's', array (
  'first' => true,
  'index' => true,
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['date']->value => $_smarty_tpl->tpl_vars['s']->value) {
$_smarty_tpl->tpl_vars['__smarty_foreach_s']->value['index']++;
$_smarty_tpl->tpl_vars['__smarty_foreach_s']->value['first'] = !$_smarty_tpl->tpl_vars['__smarty_foreach_s']->value['index'];
echo preg_replace('!^!m',str_repeat(' ',10),smarty_modifier_date_format($_smarty_tpl->tpl_vars['date']->value,'%A %e %b'));?>
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
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);
echo "\n";
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);
}
echo "\n";?>
Visit this web page for more information:&nbsp;http://www.shorelinecinema.co.nz/movies/<?php echo $_smarty_tpl->tpl_vars['n']->value['movie_id'];
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);
echo "\n\n";
echo $_smarty_tpl->tpl_vars['divider']->value;
echo "\n\n";
} else { ?><!DOCTYPE html><html lang="en-nz"><head><title>Shoreline Cinema Newsletter</title><meta http-equiv="Content-Type" content="text/html;charset=UTF-8" /><meta http-equiv="X-UA-Compatible" content="IE=edge"><meta name="viewport" content="width=device-width, initial-scale=1"><style type="text/css"><?php $_smarty_tpl->_subTemplateRender("file:inc/css/email.css", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>ul.sessions {min-height: 0!important;}</style></head><body class="SL_body"><table class="full" cellspacing="0" cellpadding="0"><thead><tr class="small-logo"><td><a class="logo hide-med" href="#" style="margin:0 auto;"><img src="https://shoreline.nz/tpl/inc/img/sl_logo.png" class="sl-logo-sm" width="316"></a></td></tr></thead></table><table class="full wrapper" cellspacing="0" cellpadding="0"><tbody><?php if ($_smarty_tpl->tpl_vars['editorial']->value) {?><tr class="featured"><td style="color:#ffffff;"><?php echo $_smarty_tpl->tpl_vars['editorial']->value;?>
</td></tr><?php }
if ($_smarty_tpl->tpl_vars['ns']->value) {?><tr class="information"><td><h1 style="font-weight:400;">Weekly Session Times</h1></td></tr><tr><td><table class="movie-times" cellspacing="0" cellpadding="10"><tbody><?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['ns']->value, 'n', false, NULL, 'n', array (
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['n']->value) {
?><tr class="movie-item"><td class="movie-poster"><a href="https://shoreline.nz/movies/<?php echo $_smarty_tpl->tpl_vars['n']->value['movie_id'];?>
/"><img src="<?php echo $_smarty_tpl->tpl_vars['n']->value['poster_url'];?>
" alt="<?php echo $_smarty_tpl->tpl_vars['n']->value['title'];?>
" style="height:281px;" border="0"></a></td><td class="movie-text" style="border-bottom: 1px solid #ffffff;"><table class="content" cellspacing="0" cellpadding="0"><tr><td style="vertical-align: top;"><h2><a href="https://shoreline.nz/movies/<?php echo $_smarty_tpl->tpl_vars['n']->value['movie_id'];?>
/" style="color:#ffffff;"><?php echo $_smarty_tpl->tpl_vars['n']->value['title'];?>
</a><span class="details">&nbsp;&nbsp;[<strong><?php echo $_smarty_tpl->tpl_vars['n']->value['classification'];?>
</strong><?php if ($_smarty_tpl->tpl_vars['n']->value['class_explanation']) {?> (<?php echo $_smarty_tpl->tpl_vars['n']->value['class_explanation'];?>
)<?php }
if ($_smarty_tpl->tpl_vars['n']->value['duration']) {?> - <?php echo $_smarty_tpl->tpl_vars['n']->value['duration'];
}?>]</span></h2><b style="font-weight:bold;"><em style="font-style:italic;color:#ffffff;"><?php if ($_smarty_tpl->tpl_vars['n']->value['comments']) {?> (<?php echo $_smarty_tpl->tpl_vars['n']->value['comments'];?>
)<?php }?></em></b><p style="color:#ffffff;"><?php echo $_smarty_tpl->tpl_vars['n']->value['synopsis'];?>
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
if ((isset($_smarty_tpl->tpl_vars['__smarty_foreach_s']->value['first']) ? $_smarty_tpl->tpl_vars['__smarty_foreach_s']->value['first'] : null)) {?><li><b style="color:#ffffff;display:inline-block;width:10.5em;"><?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['date']->value,'%A %e %b');?>
</b><?php }
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
/" style="color:#ffffff;"><?php echo $_smarty_tpl->tpl_vars['st']->value['time'];?>
</a>														<?php }
} else {
if ((isset($_smarty_tpl->tpl_vars['__smarty_foreach_s']->value['first']) ? $_smarty_tpl->tpl_vars['__smarty_foreach_s']->value['first'] : null)) {
} else {
if ($_smarty_tpl->tpl_vars['cnt']->value == 0) {?><li><b style="color:#ffffff;display:inline-block;width:10.5em;"><?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['date']->value,'%A %e %b');?>
</b><?php }
}
$_smarty_tpl->_assignInScope('cnt', $_smarty_tpl->tpl_vars['cnt']->value+1);
if (!(isset($_smarty_tpl->tpl_vars['__smarty_foreach_st']->value['first']) ? $_smarty_tpl->tpl_vars['__smarty_foreach_st']->value['first'] : null)) {?>, <?php }?><a href="https://shoreline.nz/bookings/<?php echo $_smarty_tpl->tpl_vars['st']->value['id'];?>
/" style="color:#ffffff;"><?php echo $_smarty_tpl->tpl_vars['st']->value['time'];?>
</a><?php if ($_smarty_tpl->tpl_vars['st']->value['label']) {?> <i>(<?php echo $_smarty_tpl->tpl_vars['st']->value['label'];?>
)</i><?php }
}
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?></li></ul></td></tr></table></td></tr><?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?></tbody></table></td></tr><?php }?></tbody><tfoot><tr><td class="footer"><p style="text-align:center;">To unsubscribe from this email newsletter, <a href="<!--unsub-->" id="unsubscribe">click here</a>.<br>While every attempt is made to ensure this website is accurate,<br>we are not liable for any omissions or errors.<br>Web design and content &copy; <?php echo date('Y');?>
, Shoreline Cinema Waikanae, New Zealand.</p></td></tr></tfoot></table></body></html><?php }
}
}
