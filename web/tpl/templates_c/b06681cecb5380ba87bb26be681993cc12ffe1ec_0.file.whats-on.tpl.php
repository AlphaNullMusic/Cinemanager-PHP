<?php
/* Smarty version 3.1.33, created on 2020-07-27 16:51:12
  from '/var/www/Cinemanager/web/tpl/whats-on.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_5f1e5d401f62f3_76166130',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'b06681cecb5380ba87bb26be681993cc12ffe1ec' => 
    array (
      0 => '/var/www/Cinemanager/web/tpl/whats-on.tpl',
      1 => 1574633434,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:inc/tpl/header.tpl' => 1,
    'file:inc/tpl/footer.tpl' => 1,
  ),
),false)) {
function content_5f1e5d401f62f3_76166130 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_checkPlugins(array(0=>array('file'=>'/var/www/Cinemanager/_deps/smarty/plugins/modifier.date_format.php','function'=>'smarty_modifier_date_format',),));
$_smarty_tpl->_subTemplateRender("file:inc/tpl/header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?><div class="information"><h1>What's On</h1><?php if ($_smarty_tpl->tpl_vars['now_showing']->value) {?><ul class="movie-times"><?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['now_showing']->value, 'n', false, NULL, 'n', array (
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['n']->value) {
?><li><div class="content"><div class="content-wrapper poster"><a href="/movies/<?php echo $_smarty_tpl->tpl_vars['n']->value['movie_id'];?>
/"><img src="<?php echo $_smarty_tpl->tpl_vars['n']->value['poster_url'];?>
" alt="<?php echo $_smarty_tpl->tpl_vars['n']->value['title'];?>
" width="190" border="0"></a></div><div class="content-wrapper text"><h2><a href="/movies/<?php echo $_smarty_tpl->tpl_vars['n']->value['movie_id'];?>
/"><?php echo $_smarty_tpl->tpl_vars['n']->value['title'];?>
</a><span class="details">&nbsp;&nbsp;[<strong><?php echo $_smarty_tpl->tpl_vars['n']->value['classification'];?>
</strong><?php if ($_smarty_tpl->tpl_vars['n']->value['class_explanation']) {?> (<?php echo $_smarty_tpl->tpl_vars['n']->value['class_explanation'];?>
)<?php }
if ($_smarty_tpl->tpl_vars['n']->value['duration']) {?> - <?php echo $_smarty_tpl->tpl_vars['n']->value['duration'];
}?>]</span></h2><strong><em><?php if ($_smarty_tpl->tpl_vars['n']->value['comments']) {?> (<?php echo $_smarty_tpl->tpl_vars['n']->value['comments'];?>
)<?php }?></em></strong><ul class="sessions"><?php
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
if (!(isset($_smarty_tpl->tpl_vars['__smarty_foreach_st']->value['first']) ? $_smarty_tpl->tpl_vars['__smarty_foreach_st']->value['first'] : null)) {?>, <?php }?><a href="/bookings/<?php echo $_smarty_tpl->tpl_vars['st']->value['id'];?>
/"><?php echo $_smarty_tpl->tpl_vars['st']->value['time'];?>
</a><?php if ($_smarty_tpl->tpl_vars['st']->value['label']) {?> (<?php echo $_smarty_tpl->tpl_vars['st']->value['label'];?>
)<?php }
}
} else {
if ((isset($_smarty_tpl->tpl_vars['__smarty_foreach_s']->value['first']) ? $_smarty_tpl->tpl_vars['__smarty_foreach_s']->value['first'] : null)) {
} else {
if ($_smarty_tpl->tpl_vars['cnt']->value == 0) {?><li><strong><?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['date']->value,'%A %e %b');?>
</strong><?php }
}
$_smarty_tpl->_assignInScope('cnt', $_smarty_tpl->tpl_vars['cnt']->value+1);
if (!(isset($_smarty_tpl->tpl_vars['__smarty_foreach_st']->value['first']) ? $_smarty_tpl->tpl_vars['__smarty_foreach_st']->value['first'] : null)) {?>, <?php }?><a href="/bookings/<?php echo $_smarty_tpl->tpl_vars['st']->value['id'];?>
/"><?php echo $_smarty_tpl->tpl_vars['st']->value['time'];?>
</a><?php if ($_smarty_tpl->tpl_vars['st']->value['label']) {?> <i>(<?php echo $_smarty_tpl->tpl_vars['st']->value['label'];?>
)</i><?php }
}
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?></li></ul></div></div><hr></li><?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?></ul><?php } else { ?><p>Currently we don't have any session times listed.<br />Please <a href="/contact-us/"><strong><em>contact us</em></strong></a> or check back later.</p><?php }?></div><?php $_smarty_tpl->_subTemplateRender("file:inc/tpl/footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
}
}
