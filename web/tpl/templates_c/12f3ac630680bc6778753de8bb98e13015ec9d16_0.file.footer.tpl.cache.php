<?php
/* Smarty version 3.1.33, created on 2019-12-09 23:11:52
  from '/var/www/Cinemanager/web/tpl/inc/tpl/footer.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_5dee1de80fc858_20854106',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '12f3ac630680bc6778753de8bb98e13015ec9d16' => 
    array (
      0 => '/var/www/Cinemanager/web/tpl/inc/tpl/footer.tpl',
      1 => 1575886306,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5dee1de80fc858_20854106 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->compiled->nocache_hash = '4301421435dee1de80f32a9_56561804';
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
>$('.featured-carousel').slick({slidesToShow: 1,slidesToScroll: 1,autoplay: true,autoplaySpeed: 10000,});window.onload = function() {document.getElementById("home_map").src = "https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3016.7729275756215!2d175.06186241584493!3d-40.87685717931432!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x6d40a25f6ef06be5%3A0x2d166093f029d9a9!2sShoreline+Cinema!5e0!3m2!1sen!2sus!4v1541431553996";}<?php echo '</script'; ?>
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
