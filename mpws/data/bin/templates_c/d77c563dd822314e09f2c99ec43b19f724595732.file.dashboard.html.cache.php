<?php /* Smarty version Smarty-3.1.11, created on 2012-10-24 19:39:36
         compiled from "/var/www/mpws/web/customer/toolbox/template/page/dashboard.html" */ ?>
<?php /*%%SmartyHeaderCode:16577886615088199d330cb0-04261255%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'd77c563dd822314e09f2c99ec43b19f724595732' => 
    array (
      0 => '/var/www/mpws/web/customer/toolbox/template/page/dashboard.html',
      1 => 1351096773,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '16577886615088199d330cb0-04261255',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_5088199d3c21f7_45362496',
  'variables' => 
  array (
    'CURRENT' => 0,
    'MODEL' => 0,
    'allWidgets' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5088199d3c21f7_45362496')) {function content_5088199d3c21f7_45362496($_smarty_tpl) {?><?php $_smarty_tpl->tpl_vars["allWidgets"] = new Smarty_variable($_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectTemplatePath_macro_widgetGrabber, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_widgets'=>$_smarty_tpl->tpl_vars['MODEL']->value['WIDGET']), 0));?>

<?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->{"objectTemplatePath_page_system:default"}, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_content'=>array($_smarty_tpl->tpl_vars['allWidgets']->value)), 0);?>
<?php }} ?>