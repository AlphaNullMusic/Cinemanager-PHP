<?php
/* Smarty version 3.1.33, created on 2019-11-25 17:09:27
  from '/var/www/Cinemanager/web/tpl/coming-soon.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_5ddb53f7e31b47_38239803',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '158d2735040f93f9a12d21749d817373a9a83090' => 
    array (
      0 => '/var/www/Cinemanager/web/tpl/coming-soon.tpl',
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
function content_5ddb53f7e31b47_38239803 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->compiled->nocache_hash = '19069292775ddb53f7e29457_29408403';
$_smarty_tpl->_subTemplateRender("file:inc/tpl/header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, $_smarty_tpl->cache_lifetime, array(), 0, false);
?><div class="information"><h1>Coming Soon</h1><?php if ($_smarty_tpl->tpl_vars['coming_soon']->value) {
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['coming_soon']->value, 'n');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['n']->value) {
?>		  <div class="item"><a href="/movies/<?php echo $_smarty_tpl->tpl_vars['n']->value['movie_id'];?>
/"><img src="<?php echo $_smarty_tpl->tpl_vars['n']->value['poster_url'];?>
" alt="<?php echo $_smarty_tpl->tpl_vars['n']->value['title'];?>
 Poster" height="279"></a><h2><a href="/movies/<?php echo $_smarty_tpl->tpl_vars['n']->value['movie_id'];?>
/"><?php echo $_smarty_tpl->tpl_vars['n']->value['title'];?>
</a></h2><span class="details"><?php if ($_smarty_tpl->tpl_vars['n']->value['release_date2'] != "TBC") {
echo $_smarty_tpl->tpl_vars['n']->value['release_date'];
} else { ?>Coming Soon<?php }?></span></div>		<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);
} else { ?><p>Currently we don't have any movies coming soon.<br />Please <a href="/contact-us/"><strong><em>contact us</em></strong></a> or check back later.</p><?php }?></div><?php $_smarty_tpl->_subTemplateRender("file:inc/tpl/footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, $_smarty_tpl->cache_lifetime, array(), 0, false);
}
}
