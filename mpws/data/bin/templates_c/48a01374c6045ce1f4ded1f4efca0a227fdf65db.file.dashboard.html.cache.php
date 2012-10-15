<?php /* Smarty version Smarty-3.1.11, created on 2012-10-15 23:03:09
         compiled from "/var/www/mpws/rc_1.0/web/customer/toolbox/template/page/dashboard.html" */ ?>
<?php /*%%SmartyHeaderCode:71449010507af14450a279-08849883%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '48a01374c6045ce1f4ded1f4efca0a227fdf65db' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/customer/toolbox/template/page/dashboard.html',
      1 => 1350327224,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '71449010507af14450a279-08849883',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_507af144568cf9_44656257',
  'variables' => 
  array (
    'CURRENT' => 0,
    'MODEL' => 0,
    'allWidgets' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_507af144568cf9_44656257')) {function content_507af144568cf9_44656257($_smarty_tpl) {?><?php $_smarty_tpl->tpl_vars["allWidgets"] = new Smarty_variable($_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectTemplatePath_component_widgetGrabber, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_widgets'=>$_smarty_tpl->tpl_vars['MODEL']->value['WIDGET']), 0));?>

<?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->{"objectTemplatePath_page_system:default"}, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_content'=>array($_smarty_tpl->tpl_vars['allWidgets']->value)), 0);?>
<?php }} ?>