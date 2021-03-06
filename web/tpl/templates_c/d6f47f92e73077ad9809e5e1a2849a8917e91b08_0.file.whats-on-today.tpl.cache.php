<?php
/* Smarty version 3.1.33, created on 2021-02-17 17:47:40
  from '/var/www/Cinemanager/web/tpl/whats-on-today.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_602c9fec88a4a2_92564380',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'd6f47f92e73077ad9809e5e1a2849a8917e91b08' => 
    array (
      0 => '/var/www/Cinemanager/web/tpl/whats-on-today.tpl',
      1 => 1613537203,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:inc/tpl/header.tpl' => 1,
    'file:inc/tpl/footer.tpl' => 1,
  ),
),false)) {
function content_602c9fec88a4a2_92564380 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_checkPlugins(array(0=>array('file'=>'/var/www/Cinemanager/_deps/smarty/plugins/function.cycle.php','function'=>'smarty_function_cycle',),));
$_smarty_tpl->compiled->nocache_hash = '331412973602c9fec87b971_61522358';
$_smarty_tpl->_subTemplateRender("file:inc/tpl/header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, $_smarty_tpl->cache_lifetime, array(), 0, false);
?><div class="information"><h1>What's on Today</h1><div class="movie-list"><?php if ($_smarty_tpl->tpl_vars['sessions']->value) {
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['sessions']->value, 's', false, NULL, 's', array (
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['s']->value) {
?><div class="featured-poster item"><div style="clear:<?php echo smarty_function_cycle(array('values'=>"left,none"),$_smarty_tpl);?>
;"><a href="/movies/<?php echo $_smarty_tpl->tpl_vars['s']->value['movie_id'];?>
/"><img src="<?php echo $_smarty_tpl->tpl_vars['s']->value['poster_url'];?>
" alt="<?php echo $_smarty_tpl->tpl_vars['s']->value['title'];?>
" height="279" border="0"></a><h2><a href="/movies/<?php echo $_smarty_tpl->tpl_vars['s']->value['movie_id'];?>
/"><?php echo $_smarty_tpl->tpl_vars['s']->value['title'];?>
</a> <span class="details">(<?php echo $_smarty_tpl->tpl_vars['s']->value['classification'];?>
)</span></h2><?php if ($_smarty_tpl->tpl_vars['s']->value['id_required'] == 1) {?><p class="id-required"><em>ID Required</em></p><?php }?><p><strong><?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['s']->value['sessions'], 'st', false, NULL, 'st', array (
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
if ($_smarty_tpl->tpl_vars['st']->value['label']) {?> <em>(<?php echo $_smarty_tpl->tpl_vars['st']->value['label'];?>
)</em><?php }
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?></strong></p><a class="btn dark" href="/movies/<?php echo $_smarty_tpl->tpl_vars['s']->value['movie_id'];?>
/">More details</a></div></div><?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);
} else { ?><p>Currently we don't have any session times listed.<br />Please <a href="/contact-us/"><strong><em>contact us</em></strong></a> or check back later.</p><?php }?></div></div><?php $_smarty_tpl->_subTemplateRender("file:inc/tpl/footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, $_smarty_tpl->cache_lifetime, array(), 0, false);
}
}
