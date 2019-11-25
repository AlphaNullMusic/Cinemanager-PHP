<?php /* Smarty version Smarty-3.1.13, created on 2019-11-24 22:36:28
         compiled from "I:\Cinemanager\www\Cinemanager\web\tpl\inc\tpl\footer.tpl" */ ?>
<?php /*%%SmartyHeaderCode:8815d731d59b5ff54-33421217%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '717357d2a229876ed218782ea98cbddfc6796dab' => 
    array (
      0 => 'I:\\Cinemanager\\www\\Cinemanager\\web\\tpl\\inc\\tpl\\footer.tpl',
      1 => 1574588139,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '8815d731d59b5ff54-33421217',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_5d731d59b92637_95609989',
  'variables' => 
  array (
    'home' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5d731d59b92637_95609989')) {function content_5d731d59b92637_95609989($_smarty_tpl) {?><footer><div id="text"><p>Web design and content &copy; <?php echo date('Y');?>
, Shoreline Cinema Waikanae, New Zealand. <a id="improve-visibility">Improve Visibility.</a></p></div></footer></div><script src="/tpl/inc/js/jquery-3.4.1.min.js"></script><script src="/tpl/inc/js/js.cookie-2.2.1.min.js"></script><script src="/tpl/inc/js/scripts.js"></script><?php if ($_smarty_tpl->tpl_vars['home']->value){?><script src="/tpl/inc/js/slick.min.js"></script><script>$('.featured-carousel').slick({slidesToShow: 1,slidesToScroll: 1,autoplay: true,autoplaySpeed: 10000,});</script><?php }?></body></html><?php }} ?>