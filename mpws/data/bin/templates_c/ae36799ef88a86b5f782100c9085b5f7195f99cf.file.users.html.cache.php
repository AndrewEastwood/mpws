<?php /* Smarty version Smarty-3.1.11, created on 2012-10-14 20:10:40
         compiled from "/var/www/mpws/rc_1.0/web/customer/toolbox/template/page/users.html" */ ?>
<?php /*%%SmartyHeaderCode:1857961104507af2105b4a86-18299768%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'ae36799ef88a86b5f782100c9085b5f7195f99cf' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/customer/toolbox/template/page/users.html',
      1 => 1349821108,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1857961104507af2105b4a86-18299768',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'CURRENT' => 0,
    'MODEL' => 0,
    'allWidgets' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_507af21060f550_73636334',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_507af21060f550_73636334')) {function content_507af21060f550_73636334($_smarty_tpl) {?><?php $_smarty_tpl->tpl_vars["allWidgets"] = new Smarty_variable($_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectTemplatePath_component_widgetGrabber, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_widgets'=>$_smarty_tpl->tpl_vars['MODEL']->value['WIDGET']), 0));?>

<?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->{"objectTemplatePath_page_system:default"}, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_content'=>array($_smarty_tpl->tpl_vars['allWidgets']->value)), 0);?>
<?php }} ?>