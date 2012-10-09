<?php /* Smarty version Smarty-3.1.11, created on 2012-10-10 01:19:55
         compiled from "/var/www/mpws/rc_1.0/web/customer/toolbox/template/page/tools.html" */ ?>
<?php /*%%SmartyHeaderCode:1349145481507341ce990ee7-53521124%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '6a2bd7a905030f8591c53d6372450316d46fb9b7' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/customer/toolbox/template/page/tools.html',
      1 => 1349821193,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1349145481507341ce990ee7-53521124',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_507341ce9dffb3_71627650',
  'variables' => 
  array (
    'CURRENT' => 0,
    'MODEL' => 0,
    'allWidgets' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_507341ce9dffb3_71627650')) {function content_507341ce9dffb3_71627650($_smarty_tpl) {?><?php $_smarty_tpl->tpl_vars["allWidgets"] = new Smarty_variable($_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectTemplatePath_component_widgetGrabber, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_widgets'=>$_smarty_tpl->tpl_vars['MODEL']->value['WIDGET']), 0));?>

<?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->{"objectTemplatePath_page_system:default"}, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_content'=>array($_smarty_tpl->tpl_vars['allWidgets']->value)), 0);?>


<?php }} ?>