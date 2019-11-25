<?php
/* Smarty version 3.1.33, created on 2019-11-25 17:35:42
  from '/var/www/Cinemanager/web/tpl/inc/tpl/footer.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_5ddb5a1e9a7ea3_26984011',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '12f3ac630680bc6778753de8bb98e13015ec9d16' => 
    array (
      0 => '/var/www/Cinemanager/web/tpl/inc/tpl/footer.tpl',
      1 => 1574656539,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5ddb5a1e9a7ea3_26984011 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->compiled->nocache_hash = '16006437955ddb5a1e996bc2_82084737';
?>
<footer><div id="text"><p>Web design and content &copy; <?php echo date('Y');?>
, Shoreline Cinema Waikanae, New Zealand. <a id="improve-visibility">Improve Visibility.</a></p></div></footer></div><?php echo '<script'; ?>
 src="/tpl/inc/js/jquery-3.4.1.min.js"><?php echo '</script'; ?>
><?php echo '<script'; ?>
 src="/tpl/inc/js/js.cookie-2.2.1.min.js"><?php echo '</script'; ?>
><?php echo '<script'; ?>
 src="/tpl/inc/js/scripts.js"><?php echo '</script'; ?>
><?php if ($_smarty_tpl->tpl_vars['home']->value) {
echo '<script'; ?>
 src="/tpl/inc/js/slick.min.js"><?php echo '</script'; ?>
><?php echo '<script'; ?>
>$('.featured-carousel').slick({slidesToShow: 1,slidesToScroll: 1,autoplay: true,autoplaySpeed: 10000,});<?php echo '</script'; ?>
><?php }
if ($_smarty_tpl->tpl_vars['gacode']->value) {?><!-- Global site tag (gtag.js) - Google Analytics --><?php echo '<script'; ?>
 async src="https://www.googletagmanager.com/gtag/js?id=<?php echo $_smarty_tpl->tpl_vars['gacode']->value;?>
"><?php echo '</script'; ?>
><?php echo '<script'; ?>
>window.dataLayer = window.dataLayer || [];function gtag(){dataLayer.push(arguments);}gtag('js', new Date());gtag('config', '<?php echo $_smarty_tpl->tpl_vars['gacode']->value;?>
');<?php echo '</script'; ?>
><?php }?></body></html>
<?php }
}
