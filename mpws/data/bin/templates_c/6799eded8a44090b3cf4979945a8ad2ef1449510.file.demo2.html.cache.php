<?php /* Smarty version Smarty-3.1.11, created on 2012-09-20 02:09:19
         compiled from "/var/www/mpws/rc_1.0/web/default/v1.0/templates/widget/demo2.html" */ ?>
<?php /*%%SmartyHeaderCode:1948986372504fd318bde0c4-75711130%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '6799eded8a44090b3cf4979945a8ad2ef1449510' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/templates/widget/demo2.html',
      1 => 1348096158,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1948986372504fd318bde0c4-75711130',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_504fd318be88a5_83942956',
  'variables' => 
  array (
    'CURRENT' => 0,
    'wgt_demo' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_504fd318be88a5_83942956')) {function content_504fd318be88a5_83942956($_smarty_tpl) {?><?php $_smarty_tpl->tpl_vars['wgt_demo'] = new Smarty_variable($_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectTemplatePath_widget_demo, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array(), 0));?>

DEFAULT DEMO 2<br>
<?php echo $_smarty_tpl->tpl_vars['wgt_demo']->value;?>
<?php }} ?>