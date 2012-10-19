<?php /* Smarty version Smarty-3.1.11, created on 2012-10-19 16:40:56
         compiled from "/var/www/mpws/web/customer/toolbox/template/page/tools.html" */ ?>
<?php /*%%SmartyHeaderCode:185093785650815823713297-01027093%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '5f4bd6ce7e249c98908a9aa0a02239951c907430' => 
    array (
      0 => '/var/www/mpws/web/customer/toolbox/template/page/tools.html',
      1 => 1350654047,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '185093785650815823713297-01027093',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_5081582379f297_11318600',
  'variables' => 
  array (
    'CURRENT' => 0,
    'MODEL' => 0,
    'allWidgets' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5081582379f297_11318600')) {function content_5081582379f297_11318600($_smarty_tpl) {?><?php $_smarty_tpl->tpl_vars["allWidgets"] = new Smarty_variable($_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectTemplatePath_macro_widgetGrabber, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_widgets'=>$_smarty_tpl->tpl_vars['MODEL']->value['WIDGET']), 0));?>

<?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->{"objectTemplatePath_page_system:default"}, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_content'=>array($_smarty_tpl->tpl_vars['allWidgets']->value)), 0);?>


<?php }} ?>