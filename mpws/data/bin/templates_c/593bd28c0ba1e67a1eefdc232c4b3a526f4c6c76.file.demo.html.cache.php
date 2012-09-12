<?php /* Smarty version Smarty-3.1.11, created on 2012-09-12 03:11:04
         compiled from "/var/www/mpws/rc_1.0/web/default/v1.0/templates/widget/demo.html" */ ?>
<?php /*%%SmartyHeaderCode:1386249595504fd318baebb3-12694605%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '593bd28c0ba1e67a1eefdc232c4b3a526f4c6c76' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/templates/widget/demo.html',
      1 => 1347408657,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1386249595504fd318baebb3-12694605',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_504fd318bd5de6_15803877',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_504fd318bd5de6_15803877')) {function content_504fd318bd5de6_15803877($_smarty_tpl) {?>HELLO WORLD!!!;
<?php $_smarty_tpl->_capture_stack[0][] = array('mymsg', null, null); ob_start(); ?>CAPTURE TEST FROM DEMO1<?php list($_capture_buffer, $_capture_assign, $_capture_append) = array_pop($_smarty_tpl->_capture_stack[0]);
if (!empty($_capture_buffer)) {
 if (isset($_capture_assign)) $_smarty_tpl->assign($_capture_assign, ob_get_contents());
 if (isset( $_capture_append)) $_smarty_tpl->append( $_capture_append, ob_get_contents());
 Smarty::$_smarty_vars['capture'][$_capture_buffer]=ob_get_clean();
} else $_smarty_tpl->capture_error();?>;
<?php }} ?>